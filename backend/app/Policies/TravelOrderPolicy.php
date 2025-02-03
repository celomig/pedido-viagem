<?php

namespace App\Policies;

use App\Models\Register\User;
use App\Models\TravelOrders\TravelOrder;
use Illuminate\Auth\Access\AuthorizationException;

class TravelOrderPolicy
{
    /**
     * Determine if the given travel order can be updated by the user.
     */
    public function update(User $user, TravelOrder $travelOrder): bool
    {
        // O usuário que criou o pedido pode atualizar suas informações
        if ($user->id !== $travelOrder->created_by) {
            throw new AuthorizationException('O pedido só pode ser alterado por quem criou o mesmo.');
        }

        return true;
    }

    public function updateStatus(User $user, TravelOrder $travelOrder, string $newStatus): bool
    {
        // O usuário que criou o pedido não pode atualizar o status
        if ($user->id === $travelOrder->created_by) {
            throw new AuthorizationException('Você não tem permissão para atualizar o status criados por você.');
        }

        // Se o pedido foi aprovado, ele só pode ser cancelado antes da data de partida
        if ($travelOrder->status === 'approved' && $newStatus === 'canceled') {
            if (now()->greaterThanOrEqualTo($travelOrder->departure_date)) {
                throw new AuthorizationException('O pedido não pode ser cancelado após a data de partida.');
            }
        }

        return true;
    }

    public function delete(User $user, TravelOrder $travelOrder): bool
    {
        // O usuário que criou o pedido pode remover seu pedido
        if ($user->id !== $travelOrder->created_by) {
            throw new AuthorizationException('Você não tem permissão para excluir pedidos que não foram criados por você mesmo.');
        }

        return true;
    }
}