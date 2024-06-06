<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => fake()->name(),
            'email' => 'test@test.com',
            'language' => 'english',
        ]);

        User::factory()->create([
            'name' => fake()->name(),
            'email' => 'test2@test.com',
            'language' => 'arabic',
        ]);
    }
}
