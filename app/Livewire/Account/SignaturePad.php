<?php

namespace App\Livewire\Account;

use App\Models\Signature;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class SignaturePad extends Component
{
    public $signatureData = '';
    public $hasSignature = false;

    public function mount()
    {
        $user = Auth::user();
        $latestSignature = $user->latestSignature;

        if ($latestSignature) {
            $this->signatureData = $latestSignature->signature_data;
            $this->hasSignature = true;
        }
    }

    public function saveSignature($signatureData = null)
    {
        if ($signatureData) {
            $this->signatureData = $signatureData;
        }

        $this->validate([
            'signatureData' => 'required|string',
        ]);

        $user = Auth::user();

        // Créer une nouvelle signature avec les informations de traçabilité
        Signature::create([
            'user_id' => $user->id,
            'signature_data' => $this->signatureData,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'signed_at' => now(),
        ]);

        $this->hasSignature = true;

        session()->flash('success', 'Votre signature a été enregistrée avec succès.');
    }

    public function clearSignature()
    {
        $this->signatureData = '';
        $this->hasSignature = false;

        // Dispatch un événement pour effacer le canvas
        $this->dispatch('clear-canvas');
    }

    public function deleteSignature()
    {
        $user = Auth::user();

        // Supprimer toutes les signatures de l'utilisateur
        $user->signatures()->delete();

        $this->signatureData = '';
        $this->hasSignature = false;

        session()->flash('success', 'Votre signature a été supprimée.');
    }

    public function render()
    {
        return view('livewire.account.signature-pad');
    }
}
