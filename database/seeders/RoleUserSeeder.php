<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class RoleUserSeeder extends Seeder
{
    public function run()
    {
        // Find or create roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $accountingRole = Role::firstOrCreate(['name' => 'accounting']);
        $managerRole = Role::firstOrCreate(['name' => 'manager']);

        // Assign role to test@example.com user
        $user = User::where('email', 'test@example.com')->first();
        if ($user) {
            $user->roles()->sync([$adminRole->id]);
        }
    }
}
