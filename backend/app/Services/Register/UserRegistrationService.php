<?php

namespace App\Services\Register;

use App\Models\Register\Company;
use App\Models\Register\User;
use App\Models\Register\ActivationToken;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class UserRegistrationService
{
    public function registerUserWithCompany(array $data): User
    {
        
        // Criando a empresa
        $company = Company::create([
            'name' => $data['company_name'],
            'status' => 'activing', // Status inicial da empresa
        ]);

        // Criando o usuário
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'company_id' => $company->id,
            'status' => 'inactive', // Status inicial do usuário
        ]);

        // Gerando token de ativação
        $this->createActivationToken($user);

        return $user;
    }

    private function createActivationToken(User $user): void
    {
        $token = Str::random(64); // Gerando token aleatório

        ActivationToken::create([
            'user_id' => $user->id,
            'token' => $token,
            'expires_at' => Carbon::now()->addHours(24), // Validade de 24 horas
        ]);
    }

    public function activateUserAccount($token): array
    {
        $activationToken = ActivationToken::where('token', $token)->first();

        if (!$activationToken || $activationToken->expires_at < now()) {
            \Log::warning('Tentativa de ativação com token inválido ou expirado.', ['token' => $token]);
            return ['message' => 'Token inválido ou expirado.', 'status' => 400];
        }

        $user = $activationToken->user;
        $company = $user->company;

        if ($company->status !== 'activating') {
            return [
                'message' => 'A conta já está ativada ou não está no status correto para ativação.',
                'status' => 400,
            ];
        }

        // Iniciando transação
        \DB::transaction(function () use ($user, $company, $activationToken) {
            $user->status = 'active';
            $user->save();

            $company->status = 'testing';
            $company->save();

            $activationToken->delete();
        });

        // Enviar email
        $user->notify(new \App\Notifications\TrialPeriodNotification(now()->addDays(14)));

        return [
            'message' => 'Conta ativada com sucesso! Um e-mail de boas-vindas foi enviado.',
            'status' => 200,
        ];
    }


    public function sendActivationEmail(User $user): void
    {
        $activationUrl = route('activate.account', ['token' => $user->activationToken->token]);
        
        // Passando a URL de ativação para a notificação
        $user->notify(new \App\Notifications\AccountActivationNotification($activationUrl));
    }
    

}
