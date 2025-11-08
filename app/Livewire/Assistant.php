<?php

namespace App\Livewire;

use App\Models\AiTrainer;
use App\Models\AssistantMessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class Assistant extends Component
{
    public $isOpen = false; // pas typé, ok pour Livewire
    public $input = '';
    public $messages = [];

    // Pour le streaming
    public $streamingMessageId = null; // id du message IA en cours
    public $streamingText = '';        // texte streamé en direct

    public function mount()
    {
        $this->loadMessages();
    }

    public function loadMessages()
    {
        $this->messages = AssistantMessage::where('user_id', Auth::id())
            ->orderBy('id', 'desc')
            ->get();
    }

    public function toggleChat()
    {
        $this->isOpen = ! $this->isOpen;
    }

    public function sendMessage()
    {
        if (! trim((string) $this->input)) {
            return;
        }

        // 1. On enregistre le message utilisateur
        AssistantMessage::create([
            'ai_trainer_id' => AiTrainer::find(1)->id,
            'text'          => $this->input,
            'user_id'       => Auth::id(),
            'is_ia'         => false,
        ]);

        // 2. On vide l’input pour l’UI
        $this->input = '';

        // 3. On recharge les messages
        $this->loadMessages();

        // 4. On lance la réponse IA dans un second appel Livewire
        //    (important pour que le submit ne bloque pas l’UI)
        $this->js('$wire.getIaResponse()');
    }

    protected function getIaHistoryForOllama(): array
    {
        // Historique entier, format API Ollama
        return AssistantMessage::where('user_id', Auth::id())
            ->orderBy('id') // chronologique
            ->get()
            ->map(function ($message) {
                return [
                    'role'    => $message->is_ia ? 'assistant' : 'user',
                    'content' => $message->text,
                ];
            })
            ->values()
            ->all();
    }

    public function getIaResponse()
    {
        // 1. On récupère l’historique AVANT de créer le placeholder,
        //    pour éviter d’envoyer "...." au modèle.
        $history = $this->getIaHistoryForOllama();

        // 2. On crée un message IA "vide" en base (bulle en cours)
        $rep = AssistantMessage::create([
            'ai_trainer_id' => AiTrainer::find(1)->id,
            'text'          => '',           // on remplira avec le stream
            'user_id'       => Auth::id(),
            'is_ia'         => true,
        ]);

        // On indique à Livewire quel message est en train de streamer
        $this->streamingMessageId = $rep->id;
        $this->streamingText      = '';

        // On recharge les messages pour afficher la bulle IA
        $this->loadMessages();

        // 3. Appel à Ollama en stream
        $response = Http::withOptions(['stream' => true])
            ->post('http://127.0.0.1:11434/api/chat', [
                'model'    => 'llama3',    // adapte si besoin
                'messages' => $history,
                'stream'   => true,
                'options'  => [
                    'num_predict' => 120,
                ],
            ]);

        if ($response->failed()) {
            $this->streamingText = 'Erreur de connexion à l’IA 😢';

            // Met à jour la bulle en direct
            $this->stream(
                to: 'streamingText',
                content: $this->streamingText,
                replace: true,
            );

            // Sauvegarde finale en DB
            $rep->text = $this->streamingText;
            $rep->save();

            // Reload pour aligner messages <-> DB
            $this->loadMessages();
            return;
        }

        $body   = $response->getBody();
        $buffer = '';

        // 4. Lecture du flux JSON ligne par ligne
        while (! $body->eof()) {
            $chunk = $body->read(1024);
            if ($chunk === '' || $chunk === false) {
                continue;
            }

            $buffer .= $chunk;

            while (($pos = strpos($buffer, "\n")) !== false) {
                $line   = trim(substr($buffer, 0, $pos));
                $buffer = substr($buffer, $pos + 1);

                if ($line === '') {
                    continue;
                }

                $json = json_decode($line, true);
                if (! is_array($json)) {
                    continue;
                }

                // Bout de texte de l'IA
                if (isset($json['message']['content'])) {
                    $piece = $json['message']['content'];

                    // On met à jour le texte complet
                    $this->streamingText .= $piece;

                    // On stream la nouvelle version vers le navigateur
                    $this->stream(
                        to: 'streamingText',              // property ciblée
                        content: $this->streamingText,    // tout le texte actuel
                        replace: true,                    // on remplace l'ancien
                    );
                }

                // Fin du stream (done: true)
                if (($json['done'] ?? false) === true) {
                    break 2;
                }
            }
        }

        // 5. À la fin, on sauvegarde le texte final en DB
        $rep->text = $this->streamingText;
        $rep->save();

        // 6. On recharge les messages (optionnel, pour être 100% aligné)
        $this->loadMessages();
    }

    public function render()
    {
        return view('livewire.assistant');
    }
}
