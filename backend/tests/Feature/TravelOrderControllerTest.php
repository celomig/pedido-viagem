<?php

namespace Tests\Feature;

use App\Models\TravelOrders\TravelOrder;
use App\Models\Register\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class TravelOrderControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->create()); // Apenas usuário autenticado executando os testes
    }

    /**
     * Testa a criação de uma nova ordem de viagem.
     */
    #[Test]
    public function it_can_create_travel_order()
    {
        $data = [
            'requester_name' => 'John Doe',
            'destination' => 'New City',
            'departure_date' => now()->addDays(5)->toDateString(),
            'return_date' => now()->addDays(10)->toDateString(),
            'status' => 'requested',
        ];

        $response = $this->postJson(route('travel-orders.store'), $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('travel_orders', $data);
    }

    /**
     * Testa se apenas o criador pode atualizar a ordem de viagem.
     */
    #[Test]
    public function only_creator_can_update_travel_order()
    {
        $travelOrder = TravelOrder::factory()->create(['created_by' => auth()->id()]);

        $response = $this->patchJson(route('travel-orders.update', $travelOrder), [
            'destination' => 'Updated City'
        ]);

        $response->assertStatus(200);
        $this->assertEquals('Updated City', $travelOrder->fresh()->destination);
    }


    /**
     * Testa se apenas o criador pode excluir a ordem de viagem.
     */
    #[Test]
    public function only_creator_can_delete_travel_order()
    {
        $travelOrder = TravelOrder::factory()->create(['created_by' => auth()->id()]);

        $response = $this->deleteJson(route('travel-orders.destroy', $travelOrder));

        $response->assertStatus(200);
        $this->assertSoftDeleted($travelOrder);
    }

    /**
     * Testa se usuários não autorizados não podem atualizar o status.
     */
    #[Test]
    public function only_authorized_users_can_update_status()
    {
        $travelOrder = TravelOrder::factory()->create(['status' => 'requested']);

        $response = $this->putJson(route('travel-orders.update-status', $travelOrder), [
            'status' => 'approved'
        ]);

        $response->assertStatus(403); 
    }

}
