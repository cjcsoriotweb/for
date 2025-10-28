<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class MakeSuperAdmin extends Command
{
    /**
     * Nom et signature de la commande.
     *
     * Exemple : php artisan user:superadmin user@example.com
     */
    protected $signature = 'user:superadmin {email : Adresse e-mail de l’utilisateur}';

    /**
     * Description de la commande.
     */
    protected $description = 'Passe un utilisateur en super admin (is_admin = true ou rôle superadmin)';

    /**
     * Exécution de la commande.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $this->info("🔍 Recherche de l’utilisateur avec l’e-mail : {$email}");

        $user = User::where('email', $email)->first();

        if (! $user) {
            $this->error('❌ Aucun utilisateur trouvé avec cet e-mail.');
            return 1;
        }

        // Si tu as une colonne "is_admin" dans ta table users :
        $user->superadmin = true;
        $user->save();

        // Si tu utilises un système de rôles (par ex. spatie/laravel-permission), tu peux remplacer par :
        // $user->assignRole('superadmin');

        $this->info("✅ L’utilisateur {$user->name} ({$user->email}) est maintenant super admin !");
        return 0;
    }
}
