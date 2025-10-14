<?php

namespace App\Livewire;

use Livewire\Component;

class NotificationsBell extends Component
{
    public int $limit = 10;

    protected $listeners = ['notification-read' => '$refresh']; // si tu veux rafraîchir manuellement

    public function markAllRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
    }

    public function render()
    {
        $user = auth()->user();

        return view('livewire.notifications-bell', [
            'unreadCount' => $user->unreadNotifications()->count(),
            'latest' => $user->notifications()->latest()->limit($this->limit)->get(),
        ]);
    }
}
