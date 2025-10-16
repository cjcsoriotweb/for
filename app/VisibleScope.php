<?php

namespace App;

trait VisibleScope
{
    public function scopeVisible($query)
    {
        return $query->where('visible', true);
    }

    public function scopeInvisible($query)
    {
        return $query->where('visible', true);
    }

    // Initialisation lors de new Model
    protected function initializeVisibleScope(): void
    {
        // Par ex. valeurs par défaut
        $this->attributes['visible'] = $this->attributes['visible'] ?? true;
    }

}
