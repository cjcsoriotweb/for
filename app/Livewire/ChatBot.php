<?php

namespace App\Livewire;

use App\Models\ChatWithBot;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ChatBot extends Component
{
    /**
     * @var array<int, array<string, string>>
     */
    public $messages;

    public string $body = '';

    protected array $rules = [
        'body' => 'required|string|min:1|max:500',
    ];

    public function fetchMessage(){
        $this->messages = ChatWithBot::get();
    }
    
    public function mount(): void
    {
       $this->fetchMessage();
    }

    public function sendMessage(): void
    {


        ChatWithBot::create([
            'text'=>$this->body,
            'user_id'=>Auth::user()->id,
            'see'=>false,
        ]);

        $this->body = '';


   
        
        $this->dispatch('chat-scrolled');
    }

    public function render()
    {
        return view('livewire.chat-bot');
    }


    public function look($id){
        $message = ChatWithBot::find($id);
        $message->reply = $this->generateReply($message->text);
        $message->save();
        $this->fetchMessage();
    }
    private function generateReply(string $message): string
    {
        $ch = curl_init('http://192.168.1.62:8000/api/chat/completions');

        $data = [
            'model' => 'llama3:latest',
            'messages' => [
                [
                    'role' => 'user',
                    'content' => $message, // on utilise ton $message ici
                ],
            ],
            'stream' => false,
        ];

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => ['Authorization: Bearer sk-caf6eaff4e514f47bf7dae014a37375d', 'Content-Type: application/json'],
            CURLOPT_POSTFIELDS => json_encode($data),
        ]);

        $response = curl_exec($ch);

        if ($response === false) {
            curl_close($ch);
            dd('Erreur cURL : ' . curl_error($ch));
        }

        curl_close($ch);

        // Décoder le JSON
        $decoded = json_decode($response, true);

   

        $reply = $decoded['choices'][0]['message']['content'] ?? null;

    
        return $reply ?? 'Erreur : aucune réponse trouvée';
    }
}
