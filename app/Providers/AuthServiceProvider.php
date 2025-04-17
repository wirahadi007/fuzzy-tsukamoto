<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Gate untuk admin & manajer
        Gate::define('admin-or-manajer', function (User $user) {
            return in_array($user->role, ['admin', 'manajer']);
        });

        // Gate hanya untuk manajer
        Gate::define('manajer-only', function (User $user) {
            return $user->role === 'manajer';
        });

        // Gate hanya untuk akunting
        Gate::define('akunting-only', function (User $user) {
            return $user->role === 'akunting';
        });
    }
}
