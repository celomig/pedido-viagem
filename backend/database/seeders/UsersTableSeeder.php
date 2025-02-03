<?php

namespace Database\Seeders;

use App\Models\Register\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Executa o seeder.
     */
    public function run(): void
    {
        // Cria dois usuários
        User::create([
            'name' => 'João Silva',
            'email' => 'joao@example.com',
            'password' => 'password123',
        ]);

        User::create([
            'name' => 'Maria Souza',
            'email' => 'maria@example.com',
            'password' => 'password123',
        ]);
    }
}