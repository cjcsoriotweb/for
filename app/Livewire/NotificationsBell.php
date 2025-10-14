<?php

namespace App\Livewire;

use Illuminate\Support\Carbon;
use Livewire\Component;

class NotificationsBell extends Component
{
    public int $pageSize = 15;

    // état UI
    public bool $open = false;


    // liste matérialisée côté serveur
    public array $items = [];
    public ?string $next_before = null; // ISO8601 du dernier item chargé

    public function render()
    {
        $user = auth()->user();
        $unreadCount = $user->unreadNotifications()->count();

        return view('livewire.notifications-bell', compact('unreadCount'));
    }

    /** Ouverture du menu : on charge la première page si vide */
    public function updatedOpen($isOpen): void
    {

        if ($isOpen && empty($this->items)) {
            $this->refreshList();
        }
    }

    public function refreshList(): void
    {
        
        $this->items = [];
        $this->next_before = null;
        $this->load(); // première page
    }

    public function loadMore(): void
    {
        if ($this->next_before) {
            $this->load($this->next_before);
        }
    }

    private function load(?string $before = null): void
    {
        $user = auth()->user();

        $q = $user->unreadNotifications()
            
            ->select(['id','data','read_at','created_at'])   // ← évite type, updated_at…
            ->orderByDesc('created_at')
            ->orderByDesc('id');

        if ($before) {
            $q->where('created_at', '<', \Illuminate\Support\Carbon::parse($before));
        }

        $rows = $q->limit($this->pageSize + 1)->get();

        $hasMore = $rows->count() > $this->pageSize;
        $slice   = $rows->take($this->pageSize)->values();

        foreach ($slice as $n) {
            $this->items[] = [
                'id' => (string) $n->id,
                'read_at' => $n->read_at?->toIso8601String(),
                'data' => is_array($n->data) ? $n->data : (array) $n->data, // safe
                'created_at' => $n->created_at->toIso8601String(),
                // ⚠️ plus de diffForHumans côté PHP (coûteux). On formate côté client.
                'human_created_at' => null,
            ];
        }

        $this->next_before = $hasMore
            ? optional($slice->last()?->created_at)->toIso8601String()
            : null;
    }


    public function markAllRead(): void
    {
        auth()->user()->unreadNotifications->markAsRead();
        $this->refreshList();
        // on garde la liste visible, mais tu peux aussi faire $this->refreshList();
        // pour refléter l'état lu/non-lu immédiatement
    }
}
