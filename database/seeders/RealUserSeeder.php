<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RealUserSeeder extends Seeder
{
    public function run(): void
    {
        $adminPassword = env('SEED_ADMIN_PASSWORD');
        $userPassword = env('SEED_USER_PASSWORD');

        if ($adminPassword) {
            User::updateOrCreate(
                ['username' => 'threatiq_sy'],
                [
                    'name' => 'ThreatIQ Admin',
                    'email' => env('SEED_ADMIN_EMAIL', 'threatiq_sy@threatiq.local'),
                    'password' => Hash::make($adminPassword),
                    'role' => 'admin',
                    'is_admin' => true,
                    'email_verified_at' => now(),
                ]
            );
        }

        if ($userPassword) {
            User::updateOrCreate(
                ['email' => 'threatiqsy@gmail.com'],
                [
                    'name' => 'ThreatIQ User',
                    'username' => 'threatiqsy',
                    'password' => Hash::make($userPassword),
                    'role' => 'member',
                    'is_admin' => false,
                    'email_verified_at' => now(),
                ]
            );
        }
    }
}
