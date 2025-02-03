<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\TravelOrders\TravelOrder;

class TravelOrderStatusUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    protected TravelOrder $travelOrder;

    public function __construct(TravelOrder $travelOrder)
    {
        $this->travelOrder = $travelOrder;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Atualização no status do seu pedido de viagem')
            ->greeting('Olá, ' . $notifiable->name . '!')
            ->line('O status do seu pedido de viagem foi alterado para: **' . strtoupper($this->travelOrder->status) . '**.')
            ->line('Destino: ' . $this->travelOrder->destination)
            ->line('Data de partida: ' . $this->travelOrder->departure_date->format('d/m/Y'))
            ->line('Data de retorno: ' . $this->travelOrder->return_date->format('d/m/Y'))
            ->action('Ver Pedido', url('/travel-orders/' . $this->travelOrder->id))
            ->line('Se precisar de mais informações, entre em contato com a equipe responsável.')
            ->salutation('Atenciosamente, '.config('app.name'));
    }
}
