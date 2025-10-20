<?php

namespace App\Notifications;

use App\Models\Team;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TeamPhotoDeleted extends Notification
{
    use Queueable;

    public function __construct(
        public Team $team,        // équipe concernée
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
            'title' => 'Photo d’équipe supprimée',
            'message' => 'La photo de l’équipe « '.$this->team->name.' » a été supprimée.',
            'by' => [
                'id' => auth()->id(),
                'name' => auth()->user()->name,
            ],
            'team' => [
                'id' => $this->team->id,
                'name' => $this->team->name,
            ],
        ];
    }

    /* Optionnel si tu veux aussi un email
    public function toMail($notifiable)
    {
        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject('Photo d’équipe supprimée')
            ->greeting('Salut '.$notifiable->name)
            ->line('La photo de l’équipe « '.$this->team->name.' » a été supprimée.')
            ->action('Ouvrir', url('/'))
            ->line('À bientôt !');
    }
    */
}
