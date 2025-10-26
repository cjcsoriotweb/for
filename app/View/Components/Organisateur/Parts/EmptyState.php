<?php

namespace App\View\Components\Organisateur\Parts;

use Illuminate\View\Component;
use Illuminate\View\View;

class EmptyState extends Component
{
    public string $icon;
    public string $title;
    public string $description;
    public ?string $action;
    public ?string $actionText;
    public ?string $actionUrl;

    public function __construct(
        string $icon = 'book',
        string $title = 'Aucun élément',
        string $description = 'Aucun élément n\'est disponible pour le moment.',
        ?string $action = null,
        ?string $actionText = null,
        ?string $actionUrl = null
    ) {
        $this->icon = $icon;
        $this->title = $title;
        $this->description = $description;
        $this->action = $action;
        $this->actionText = $actionText;
        $this->actionUrl = $actionUrl;
    }

    public function render(): View
    {
        return view('clean.organisateur.parts.empty-state', [
            'icon' => $this->icon,
            'title' => $this->title,
            'description' => $this->description,
            'action' => $this->action,
            'actionText' => $this->actionText,
            'actionUrl' => $this->actionUrl,
        ]);
    }
}
