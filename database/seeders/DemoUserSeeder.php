<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@threatiq.demo'],
            [
                'name' => 'ThreatIQ Admin',
                'username' => 'admin',
                'password' => Hash::make('Admin123!'),
                'role' => 'admin',
                'is_admin' => true,
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'demo@threatiq.demo'],
            [
                'name' => 'Demo User',
                'username' => 'demo',
                'password' => Hash::make('Demo123!'),
                'role' => 'member',
                'is_admin' => false,
                'email_verified_at' => now(),
            ]
        );
    }
}
