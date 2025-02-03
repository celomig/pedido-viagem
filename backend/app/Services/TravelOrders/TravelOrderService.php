<?php

namespace App\Services\TravelOrders;

use App\Models\TravelOrders\TravelOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\TravelOrderStatusUpdated;

class TravelOrderService
{
    public function getAllTravelOrders()
    {
        return TravelOrder::all();
    }

    /**
     * Get the filtered travel orders.
     *
     * @param  Request $request
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getFilteredTravelOrders(Request $request)
    {
        $query = TravelOrder::query();

        if ($request->has('requester_name')) {
            $query->where('requester_name', 'like', '%' . $request->input('requester_name') . '%');
        }

        if ($request->has('destination')) {
            $query->where('destination', 'like', '%' . $request->input('destination') . '%');
        }

        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->has('date_range') && is_array($request->input('date_range'))) {
            $query->whereBetween('departure_date', [$request->input('date_range')['start'], $request->input('date_range')['end']])
                  ->orWhereBetween('return_date', [$request->input('date_range')['start'], $request->input('date_range')['end']]);
        }

        return $query->get();
    }

    public function getTravelOrderById($id)
    {
        return TravelOrder::findOrFail($id);
    }

    public function createTravelOrder(array $data)
    {
        $data['status'] = 'requested';
        $data['created_by'] = Auth::id();
        return TravelOrder::create($data);
    }

    public function updateTravelOrder($id, array $data)
    {
        $travelOrder = TravelOrder::findOrFail($id);

        $data['updated_by'] = Auth::id();
        $travelOrder->update($data);

        return $travelOrder;
    }

    public function updateTravelOrderStatus(int $id, string $newStatus): TravelOrder
    {
        $travelOrder = TravelOrder::findOrFail($id);
    
        // Atualiza o status e registra quem fez a alteração
        $travelOrder->update([
            'status' => $newStatus,
            'updated_by' => Auth::id(),
        ]);
    
        // Notifica o usuário que criou o pedido sobre a atualização do status
        if ($travelOrder->creator) {
            $travelOrder->creator->notify(new TravelOrderStatusUpdated($travelOrder));
        }
    
        return $travelOrder;
    }
    
    public function deleteTravelOrder($id): void
    {
        $travelOrder = TravelOrder::findOrFail($id);
        $travelOrder->deleted_by = Auth::id();
        $travelOrder->save();
        $travelOrder->delete();
    }


}