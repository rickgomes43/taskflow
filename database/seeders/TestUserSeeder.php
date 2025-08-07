<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@taskflow.com',
            'password' => Hash::make('password123'),
        ]);

        User::create([
            'name' => 'Test User',
            'email' => 'user@taskflow.com',
            'password' => Hash::make('password123'),
        ]);

        // Create additional test users
        User::factory(5)->create();
    }
}
