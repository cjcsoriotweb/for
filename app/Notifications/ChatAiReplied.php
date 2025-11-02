<?php

namespace App\Notifications;

use App\Models\Chat;
use Illuminate\Notifications\Notification;

class ChatAiReplied extends Notification
{
    public function __construct(
        private readonly Chat $assistantMessage,
        private readonly Chat $sourceMessage
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        $assistant = $this->assistantMessage->senderIa;
        $assistantName = $assistant?->name ?? 'Assistant IA';
        $assistantId = $assistant?->id;
        $assistantExcerpt = (string) str($this->assistantMessage->content)->trim()->limit(200, '...');
        $userMessagePreview = (string) str($this->sourceMessage->content)->trim()->limit(160, '...');

        return [
            'type' => 'chat_ai_reply',
            'title' => sprintf('Reponse de %s', $assistantName),
            'message' => $assistantExcerpt === ''
                ? 'Un assistant IA vous a repondu.'
                : sprintf('"%s"', $assistantExcerpt),
            'assistant_id' => $assistantId,
            'assistant_name' => $assistantName,
            'assistant_message_id' => $this->assistantMessage->id,
            'assistant_message_excerpt' => $assistantExcerpt,
            'source_message_id' => $this->sourceMessage->id,
            'source_message_excerpt' => $userMessagePreview,
            'contact' => $assistantId ? [
                'type' => 'ai',
                'id' => 'ai_'.$assistantId,
            ] : null,
            'sent_at' => $this->assistantMessage->created_at?->toIso8601String(),
        ];
    }

    /**
     * Array representation fallback (keeps compatibility with other channels).
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}
