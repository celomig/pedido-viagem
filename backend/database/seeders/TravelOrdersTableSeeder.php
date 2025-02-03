<?php

namespace Database\Seeders;

use App\Models\TravelOrders\TravelOrder;
use App\Models\Register\User;
use Illuminate\Database\Seeder;

class TravelOrdersTableSeeder extends Seeder
{
    /**
     * Executa o seeder.
     */
    public function run(): void
    {
        // Obtém os usuários criados anteriormente
        $user1 = User::where('email', 'joao@example.com')->first();
        $user2 = User::where('email', 'maria@example.com')->first();

        // Cria quatro pedidos de viagem
        TravelOrder::create([
            'requester_name' => 'João Silva',
            'destination' => 'São Paulo',
            'departure_date' => now()->addDays(5),
            'return_date' => now()->addDays(10),
            'status' => 'requested',
            'created_by' => $user1->id,
        ]);

        TravelOrder::create([
            'requester_name' => 'Maria Souza',
            'destination' => 'Rio de Janeiro',
            'departure_date' => now()->addDays(7),
            'return_date' => now()->addDays(14),
            'status' => 'approved',
            'created_by' => $user2->id,
        ]);

        TravelOrder::create([
            'requester_name' => 'João Silva',
            'destination' => 'Belo Horizonte',
            'departure_date' => now()->addDays(3),
            'return_date' => now()->addDays(8),
            'status' => 'canceled',
            'created_by' => $user1->id,
        ]);

        TravelOrder::create([
            'requester_name' => 'Maria Souza',
            'destination' => 'Curitiba',
            'departure_date' => now()->addDays(10),
            'return_date' => now()->addDays(15),
            'status' => 'requested',
            'created_by' => $user2->id,
        ]);
    }
}