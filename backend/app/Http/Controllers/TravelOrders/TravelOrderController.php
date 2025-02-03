<?php

namespace App\Http\Controllers\TravelOrders;

use App\Http\Controllers\Controller;
use App\Http\Resources\TravelOrders\TravelOrderResource;
use App\Services\TravelOrders\TravelOrderService;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TravelOrderController extends Controller
{
    use AuthorizesRequests;
    
    protected TravelOrderService $travelOrderService;

    public function __construct(TravelOrderService $travelOrderService)
    {
        $this->travelOrderService = $travelOrderService;
    }

    /**
     * List all travel orders with optional filters.
     *
     * @param Request $request
     * @group Travel Orders
     * 
     * @response 200 {
     *  "data": [
     *    {
     *      "id": 1,
     *      "requester_name": "Maria Souza",
     *      "destination": "Rio de Janeiro",
     *      "departure_date": "2025-02-09",
     *      "return_date": "2025-02-16",
     *      "status": "approved",
     *      "created_at": "2025-02-02 23:17:48",
     *      "updated_at": "2025-02-02 23:17:48",
     *      "created_by": { "id": 2, "name": "Maria Souza" }
     *    }
     *  ],
     *  "message": {
     *    "text": "Pedidos de viagem listados com sucesso.",
     *    "type": "info"
     *  }
     * }
     */
    public function index(Request $request)
    {
        $travelOrders = $this->travelOrderService->getFilteredTravelOrders($request);
        return response()->json(TravelOrderResource::collection($travelOrders));
    }

    /**
     * Show a specific travel order by ID.
     *
     * @param int $id
     * @group Travel Orders
     * 
     * @response 200 {
     *  "data": {
     *    "id": 2,
     *    "requester_name": "Maria Souza",
     *    "destination": "Rio de Janeiro",
     *    "departure_date": "2025-02-09",
     *    "return_date": "2025-02-16",
     *    "status": "approved",
     *    "created_at": "2025-02-02 23:17:48",
     *    "updated_at": "2025-02-02 23:17:48",
     *    "created_by": { "id": 2, "name": "Maria Souza" }
     *  },
     *  "message": {
     *    "text": "Pedido de viagem encontrado com sucesso.",
     *    "type": "info"
     *  }
     * }
     */
    public function show($id)
    {
        $travelOrder = $this->travelOrderService->getTravelOrderById($id);
        return response()->json(new TravelOrderResource($travelOrder));
    }

    /**
     * Store a new travel order.
     *
     * @param Request $request
     * @group Travel Orders
     * 
     * @bodyParam requester_name string required O nome do solicitante. Example: Maria Souza
     * @bodyParam destination string required O destino da viagem. Example: Rio de Janeiro
     * @bodyParam departure_date date required A data de partida. Example: 2025-02-09
     * @bodyParam return_date date required A data de retorno. Example: 2025-02-16
     * 
     * @response 201 {
     *  "message": "Pedido criado com sucesso!",
     *  "data": {
     *    "id": 2,
     *    "requester_name": "Maria Souza",
     *    "destination": "Rio de Janeiro",
     *    "departure_date": "2025-02-09",
     *    "return_date": "2025-02-16",
     *    "status": "requested",
     *    "created_at": "2025-02-02 23:17:48",
     *    "updated_at": "2025-02-02 23:17:48",
     *    "created_by": { "id": 2, "name": "Maria Souza" }
     *  }
     * }
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'requester_name' => 'required|string',
            'destination' => 'required|string',
            'departure_date' => 'required|date',
            'return_date' => 'required|date',
        ]);

        $travelOrder = $this->travelOrderService->createTravelOrder($data);
        return response()->json([
            'message' => 'Pedido criado com sucesso!',
            'data' => new TravelOrderResource($travelOrder)
        ], 201);
    }

    /**
     * Update an existing travel order.
     *
     * @param Request $request
     * @param int $id
     * @group Travel Orders
     * 
     * @bodyParam requester_name string O nome do solicitante.
     * @bodyParam destination string O destino da viagem.
     * @bodyParam departure_date date A data de partida.
     * @bodyParam return_date date A data de retorno.
     * @bodyParam status string O status da viagem. Exemplo: approved
     * 
     * @response 200 {
     *  "message": "Pedido atualizado com sucesso!",
     *  "data": {
     *    "id": 2,
     *    "requester_name": "Maria Souza",
     *    "destination": "Rio de Janeiro",
     *    "departure_date": "2025-02-09",
     *    "return_date": "2025-02-16",
     *    "status": "approved",
     *    "created_at": "2025-02-02 23:17:48",
     *    "updated_at": "2025-02-02 23:17:48",
     *    "created_by": { "id": 2, "name": "Maria Souza" }
     *  }
     * }
     */
    public function update(Request $request, $id)
    {
        $travelOrder = $this->travelOrderService->getTravelOrderById($id);

        // Verifica a política antes de permitir a atualização
        $this->authorize('update', $travelOrder);

        $data = $request->validate([
            'requester_name' => 'sometimes|string',
            'destination' => 'sometimes|string',
            'departure_date' => 'sometimes|date',
            'return_date' => 'sometimes|date',
            'status' => 'sometimes|in:requested,approved,canceled',
        ]);

        $updatedTravelOrder = $this->travelOrderService->updateTravelOrder($id, $data);

        return response()->json([
            'message' => 'Pedido atualizado com sucesso!',
            'data' => new TravelOrderResource($updatedTravelOrder),
        ]);
    }


    /**
     * Update the status of a travel order.
     *
     * @param Request $request
     * @param int $id
     * @group Travel Orders
     * 
     * @bodyParam status string required O novo status da viagem. Exemplo: approved
     * 
     * @response 200 {
     *  "message": "Status do pedido atualizado com sucesso!",
     *  "data": {
     *    "id": 2,
     *    "requester_name": "Maria Souza",
     *    "destination": "Rio de Janeiro",
     *    "departure_date": "2025-02-09",
     *    "return_date": "2025-02-16",
     *    "status": "approved",
     *    "created_at": "2025-02-02 23:17:48",
     *    "updated_at": "2025-02-02 23:17:48",
     *    "created_by": { "id": 2, "name": "Maria Souza" }
     *  }
     * }
     */
    public function updateStatus(Request $request, $id)
    {
        $travelOrder = $this->travelOrderService->getTravelOrderById($id);
        
        $data = $request->validate([
            'status' => 'required|in:approved,canceled',
        ]);
    
        // Verifica a policy antes de atualizar o status
        $this->authorize('updateStatus', [$travelOrder, $data['status']]);
    
        $updatedTravelOrder = $this->travelOrderService->updateTravelOrderStatus($id, $data['status']);
    
        return response()->json([
            'message' => 'Status do pedido atualizado com sucesso!',
            'data' => new TravelOrderResource($updatedTravelOrder)
        ]);
    }

    /**
     * Remove a travel order.
     *
     * @param int $id
     * @group Travel Orders
     * 
     * @response 200 {
     *  "message": "Pedido removido com sucesso!"
     * }
     */
    public function destroy($id)
    {
        $travelOrder = $this->travelOrderService->getTravelOrderById($id);

        // Verifica a policy antes de permitir a exclusão
        $this->authorize('delete', $travelOrder);

        $this->travelOrderService->deleteTravelOrder($id);

        return response()->json([
            'message' => 'Pedido removido com sucesso!'
        ], 200);
    }


}
