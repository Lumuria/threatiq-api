<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AttackSeeder::class,
            PreventionSeeder::class,
            AwarenessSeeder::class,
            NewsSeeder::class,
            DemoUserSeeder::class,
            RealUserSeeder::class,
        ]);
    }
}