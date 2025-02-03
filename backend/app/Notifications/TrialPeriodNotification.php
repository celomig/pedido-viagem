<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TrialPeriodNotification extends Notification
{
    use Queueable;

    private $trialEndDate;

    /**
     * Create a new notification instance.
     */
    public function __construct($trialEndDate)
    {
        $this->trialEndDate = $trialEndDate;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Bem-vindo ao Período de Testes - '. config('app.name'))
            ->view('emails.trial_notification', [
                'user' => $notifiable,
                'trialEndDate' => $this->trialEndDate,
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
