<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();
        //test
        \App\Models\User::factory()->create([
            'name' => 'Admin user',
            'email' => 'admin@example.com',
            'password' => bcrypt("password6$")
        ]);
    }
}
