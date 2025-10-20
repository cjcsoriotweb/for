<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class UserMentioned extends Notification
{
    use Queueable;

    public function __construct(
        public int $mentionerId,          // id de l’auteur de la mention
        public string $mentionerName,     // nom affiché
        public string $context,           // ex: "Message dans #général"
        public ?string $url = null        // lien vers le message/page
    ) {}

    /** Channels utilisés */
    public function via(object $notifiable): array
    {
        return ['database']; // tu peux ajouter 'mail' si tu veux
    }

    /** Payload stocké en DB (colonne data JSON) */
    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'Tu as été mentionné·e',
            'message' => $this->mentionerName.' t’a mentionné·e. '.$this->context,
            'url' => $this->url,
            'by' => [
                'id' => $this->mentionerId,
                'name' => $this->mentionerName,
            ],
        ];
    }

    /* Optionnel si tu veux aussi un email
    public function toMail($notifiable)
    {
        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject('Tu as été mentionné·e')
            ->greeting('Salut '.$notifiable->name)
            ->line($this->mentionerName.' t’a mentionné·e. '.$this->context)
            ->action('Ouvrir', $this->url ?? url('/'))
            ->line('À bientôt !');
    }
    */
}
