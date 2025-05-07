<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class RoleServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        Gate::define('admin', function(User $user) {
            return $user->role === 'admin';
        });

        Gate::define('accounting', function(User $user) {
            return $user->role === 'accounting';
        });

        Gate::define('manager', function(User $user) {
            return $user->role === 'manager';
        });
    }
}
