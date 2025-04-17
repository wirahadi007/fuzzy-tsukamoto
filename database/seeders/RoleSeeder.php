<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // Create roles
        $adminRole = Role::create(['name' => 'admin']);
        $accountingRole = Role::create(['name' => 'accounting']);
        $managerRole = Role::create(['name' => 'manager']);

        // Assign admin role to your test user
        $user = User::where('email', 'test@example.com')->first();
        if ($user) {
            $user->roles()->attach($adminRole);
        }
    }
}