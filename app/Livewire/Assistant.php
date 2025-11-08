<?php

namespace App\Livewire;

use App\Models\AiTrainer;
use App\Models\AssistantMessage;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Client\ConnectionException;
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
    public $streamingText = ''; // texte streamé en direct

    // État des serveurs (pour affichage éventuel dans la vue)
    public $serverAURL = "http://ollama.goodview.fr:1234";
    public $serverBURL = "http://ollama.goodview.fr:12345";

    public $serverA = null; // true = OK, false = KO, null = pas encore testé
    public $serverB = null;

    protected function pingServer(string $baseUrl): bool
    {
        try {
            // Endpoint léger : à adapter si besoin (/api/tags ou /api/version, etc.)
            $response = Http::timeout(2)->get(rtrim($baseUrl, '/') . '/api/tags');

            return $response->ok();
        } catch (ConnectionException $e) {
            return false;
        } catch (\Throwable $e) {
            return false;
        }
    }

    public function checkServersStatus(): void
    {
        $this->serverA = $this->pingServer($this->serverAURL);
        $this->serverB = $this->pingServer($this->serverBURL);
    }
    public function clean()
    {
        AssistantMessage::where('user_id', Auth::id())->orderBy('id', 'desc')->delete();
    }

    public function mount()
    {
        $this->loadMessages();
    }

    public function loadMessages()
    {
        $this->messages = AssistantMessage::where('user_id', Auth::id())->orderBy('id', 'desc')->get();
    }

    public function toggleChat()
    {
        $this->isOpen = !$this->isOpen;
    }

    public function sendMessage()
    {
        if (!trim((string) $this->input)) {
            return;
        }

        // 1. On enregistre le message utilisateur
        AssistantMessage::create([
            'ai_trainer_id' => AiTrainer::find(1)->id,
            'text' => $this->input,
            'user_id' => Auth::id(),
            'is_ia' => false,
        ]);

        // 2. On vide l’input pour l’UI
        $this->input = '';

        // 3. On recharge les messages
        $this->loadMessages();

        // 4. On lance la réponse IA dans un second appel Livewire
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
                    'role' => $message->is_ia ? 'assistant' : 'user',
                    'content' => $message->text,
                ];
            })
            ->values()
            ->all();
    }

    /**
     * Petit helper pour mettre à jour le texte streamé avec un message de statut.
     */
    protected function streamStatus(string $text, bool $append = false): void
    {
        $this->streamingText = $append ? $this->streamingText . $text : $text;

        $this->stream(to: 'streamingText', content: $this->streamingText, replace: true);
    }

    /**
     * Tente d'appeler un serveur Ollama :
     *  - affiche un message de tentative
     *  - applique un timeout de connexion court (avoid "ça tourne dans le vide")
     *  - met à jour la variable $serverA / $serverB (true/false)
     *
     * @param string      $tentativeLabel      Texte affiché avant l'appel
     * @param string|null $failLabel           Texte affiché en cas d'échec
     * @param string      $url                 URL du serveur Ollama
     * @param string      $model               Nom du modèle
     * @param array       $history             Historique du chat
     * @param string      $serverFlagProperty  "serverA" ou "serverB"
     *
     * @return Response|null
     */
    protected function tryOllamaServer(string $tentativeLabel, ?string $failLabel, string $url, string $model, array $history, string $serverFlagProperty): ?Response
    {
        // Par défaut, on considère le serveur KO tant que ça n'a pas marché
        $this->{$serverFlagProperty} = false;

        // Affiche le message de tentative
        $this->streamStatus($tentativeLabel, append: true);
        sleep(1);

        try {
            $response = Http::withOptions([
                'stream' => true,
                // Timeout rapide si le serveur est down ou ne répond pas à la connexion
                'connect_timeout' => 2, // 2 secondes pour établir la connexion
                'timeout' => 60, // timeout global raisonnable (tu peux ajuster)
            ])->post($url, [
                'model' => $model,
                'messages' => $history,
                'stream' => true,
                'options' => [
                    'num_predict' => 120,
                ],
            ]);
        } catch (ConnectionException $e) {
            // Connexion impossible / timeout de connexion
            if ($failLabel !== null) {
                $this->streamStatus($failLabel, append: true);
            }

            return null;
        }

        // Si la réponse HTTP est en erreur
        if ($response->failed()) {
            if ($failLabel !== null) {
                $this->streamStatus($failLabel, append: true);
            }

            return null;
        }

        // Succès : on marque ce serveur comme OK
        $this->{$serverFlagProperty} = true;

        // Et on ajoute une petite mention OK
        $this->streamStatus(" (OK)\n", append: true);

        return $response;
    }

    public function getIaResponse()
    {
        // Réinitialise l'état des serveurs
        $this->serverA = null;
        $this->serverB = null;

        // 1. On récupère l’historique AVANT de créer le placeholder
        $history = $this->getIaHistoryForOllama();

        // 2. On crée un message IA "vide" en base (bulle en cours)
        $rep = AssistantMessage::create([
            'ai_trainer_id' => AiTrainer::find(1)->id,
            'text' => '', // on remplira avec le stream
            'user_id' => Auth::id(),
            'is_ia' => true,
        ]);

        // On indique à Livewire quel message est en train de streamer
        $this->streamingMessageId = $rep->id;
        $this->streamingText = '';

        // On recharge les messages pour afficher la bulle IA
        $this->loadMessages();

        // --- Tentative serveur A (PC) ---
        $this->streamStatus("(Tentative connexion serveur A...)\n");

        $response = $this->tryOllamaServer(tentativeLabel: '[Serveur A - PC]', failLabel: "\nÉchec serveur A, tentative connexion serveur B...", url: $this->serverAURL.'/api/chat', model: 'llama3', history: $history, serverFlagProperty: 'serverA');

        // --- Si serveur A KO, tentative serveur B (NAS) ---
        if ($response === null) {
            $response = $this->tryOllamaServer(tentativeLabel: "\n[Serveur B - NAS]", failLabel: "\nÉchec serveur B.", url: $this->serverBURL.'/api/chat', model: 'gamma3:1b', history: $history, serverFlagProperty: 'serverB');
        }

        // --- Si aucun serveur ne répond, on affiche une erreur propre ---
        if ($response === null || $response->failed()) {
            $this->streamStatus("\nErreur de connexion à l’IA 😢", append: true);

            // Sauvegarde finale en DB
            $rep->text = $this->streamingText;
            $rep->save();

            // Reload pour aligner messages <-> DB
            $this->loadMessages();
            return;
        }

        // À partir d'ici, on a une réponse OK et streamée
        $body = $response->getBody();
        $buffer = '';

        // 4. Lecture du flux JSON ligne par ligne
        while (!$body->eof()) {
            $chunk = $body->read(1024);
            if ($chunk === '' || $chunk === false) {
                continue;
            }

            $buffer .= $chunk;

            while (($pos = strpos($buffer, "\n")) !== false) {
                $line = trim(substr($buffer, 0, $pos));
                $buffer = substr($buffer, $pos + 1);

                if ($line === '') {
                    continue;
                }

                $json = json_decode($line, true);
                if (!is_array($json)) {
                    continue;
                }

                // Bout de texte de l'IA
                if (isset($json['message']['content'])) {
                    $piece = $json['message']['content'];

                    // On met à jour le texte complet
                    $this->streamingText .= $piece;

                    // On stream la nouvelle version vers le navigateur
                    $this->stream(
                        to: 'streamingText', // property ciblée
                        content: $this->streamingText, // tout le texte actuel
                        replace: true, // on remplace l'ancien
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
