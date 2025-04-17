<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Create Admin
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);
        $admin->roles()->attach(Role::where('name', 'admin')->first());

        // Create Manager
        $manager = User::create([
            'name' => 'Manager User',
            'email' => 'manager@example.com',
            'password' => Hash::make('password'),
        ]);
        $manager->roles()->attach(Role::where('name', 'manager')->first());

        // Create Accounting
        $accounting = User::create([
            'name' => 'Accounting User',
            'email' => 'accounting@example.com',
            'password' => Hash::make('password'),
        ]);
        $accounting->roles()->attach(Role::where('name', 'accounting')->first());
    }
}