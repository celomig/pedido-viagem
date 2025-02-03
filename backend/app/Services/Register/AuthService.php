<?php

namespace App\Services\Register;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function authenticate(array $credentials)
    {
        if (!Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => ['As credenciais fornecidas estão incorretas.'],
            ]);
        }

        return Auth::user();
    }

    public function logout(User $user): void
    {
        $user->currentAccessToken()->delete();
    }
}
