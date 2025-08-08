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
        User::firstOrCreate(
            ['email' => 'admin@taskflow.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]
        );

        User::firstOrCreate(
            ['email' => 'colaborador@taskflow.com'],
            [
                'name' => 'Colaborador User',
                'password' => Hash::make('password123'),
                'role' => 'colaborador',
            ]
        );

        User::firstOrCreate(
            ['email' => 'cliente@taskflow.com'],
            [
                'name' => 'Cliente User',
                'password' => Hash::make('password123'),
                'role' => 'cliente',
            ]
        );

        User::firstOrCreate(
            ['email' => 'user@taskflow.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('password123'),
                'role' => 'colaborador', // default role
            ]
        );

        // Update existing users to have proper roles if they don't have one
        User::whereNull('role')->orWhere('role', '')->update(['role' => 'colaborador']);
    }
}
