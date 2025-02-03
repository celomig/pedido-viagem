<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CustomResetPasswordNotification extends Notification
{
    use Queueable;

    protected $token;

    /**
     * Create a new notification instance.
     */
    public function __construct($token)
    {
        $this->token = $token;
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
        // Gerar o link de redefinição de senha
        // Gerar o link de redefinição de senha
        $url = url(route('password.reset', $this->token, false));

        // Enviar e-mail com o Blade personalizado
        return (new MailMessage)
                    ->subject('Redefinir sua Senha - ' . config('app.name'))
                    ->view('emails.reset_password', [
                        'user' => $notifiable, 
                        'activationUrl' => $url, 
                        'token' => $this->token // Passando o token para a view
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
