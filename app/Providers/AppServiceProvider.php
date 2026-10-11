<?php

namespace App\Providers;
    use App\Models\User;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */

public function boot(): void
{
    // Crée ou met à jour l'admin automatiquement au démarrage
    User::firstOrCreate(
        ['email' => 'chepitimayassecurite@gmail.com'],
        [
            'name' => 'Admin Chepitimayas Sécurité',
            'password' => Hash::make('Password123'),
        ]
    );
}
}
