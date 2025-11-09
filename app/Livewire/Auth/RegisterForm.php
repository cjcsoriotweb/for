<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class RegisterForm extends Component
{
    public $currentStep = 1;

    public $name = '';

    public $email = '';

    public $password = '';

    public $terms = false;

    public $totalSteps = 4;

    protected $listeners = ['nextStep', 'previousStep'];

    public function mount()
    {
        $this->currentStep = 1;
    }

    public function nextStep()
    {
        $this->validateCurrentStep();

        if ($this->currentStep < $this->totalSteps) {
            $this->currentStep++;
        }
    }

    public function previousStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    public function goToStep($step)
    {
        if ($step >= 1 && $step <= $this->totalSteps && $step <= $this->getMaxAllowedStep()) {
            $this->currentStep = $step;
        }
    }

    protected function validateCurrentStep()
    {
        $rules = [];

        switch ($this->currentStep) {
            case 1:
                $rules = [
                    'name' => 'required|string|min:2|max:255',
                ];
                break;
            case 2:
                $rules = [
                    'email' => 'required|email|unique:users,email',
                ];
                break;
            case 3:
                $rules = [
                    'password' => 'required|string|min:6',
                ];
                break;
            case 4:
                // Pas de validation pour l'étape 4 - juste présentation des conditions
                break;
        }

        $this->validate($rules);
    }

    public function acceptRegister()
    {
        // Log pour déboguer
        Log::info('Méthode register() appelée', [
            'current_step' => $this->currentStep,
            'name' => $this->name,
            'email' => $this->email,
            'password_length' => strlen($this->password),
        ]);

        try {
            $this->validate([
                'name' => 'required|string|min:2|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:6',
            ]);

            Log::info('Validation passée');

            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
            ]);

            Log::info('Utilisateur créé avec ID: '.$user->id);

            Auth::login($user);

            Log::info('Utilisateur connecté');

            session()->flash('success', 'Inscription réussie ! Bienvenue sur la plateforme.');

            return redirect()->route('user.dashboard');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Erreur de validation', ['errors' => $e->errors()]);
            $this->currentStep = $this->determineStepFromErrors($e->errors());
            throw $e; // Laisser Livewire gérer les erreurs de validation
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'inscription : '.$e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            session()->flash('error', 'Une erreur est survenue lors de l\'inscription. Veuillez réessayer.');
            $this->currentStep = 1;

            return;
        }
    }

    protected function getMaxAllowedStep()
    {
        $maxStep = 1;

        if (! empty($this->name)) {
            $maxStep = 2;
        }

        if (! empty($this->email)) {
            $maxStep = 3;
        }

        if (! empty($this->password)) {
            $maxStep = 4;
        }

        return $maxStep;
    }

    protected function determineStepFromErrors(array $errors)
    {
        $firstField = array_key_first($errors);

        return match ($firstField) {
            'name' => 1,
            'email' => 2,
            'password' => 3,
            default => 1,
        };
    }

    public function getPasswordStrength()
    {
        $length = strlen((string) $this->password);

        if ($length < 3) {
            return ['level' => 'weak', 'percentage' => 25, 'color' => 'red', 'text' => 'Faible'];
        } elseif ($length < 6) {
            return ['level' => 'medium', 'percentage' => 50, 'color' => 'yellow', 'text' => 'Moyen'];
        } elseif ($length < 10) {
            return ['level' => 'strong', 'percentage' => 75, 'color' => 'blue', 'text' => 'Valide'];
        } else {
            return ['level' => 'very-strong', 'percentage' => 100, 'color' => 'green', 'text' => 'Très bien'];
        }
    }

    public function render()
    {
        return view('livewire.auth.register-form');
    }
}
