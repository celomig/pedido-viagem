<?php

namespace App\Http\Controllers\Register;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\JsonResponse;
use App\Notifications\CustomResetPasswordNotification;
use App\Models\Register\User;


class ForgotPasswordController extends Controller
{
    /**
     * Envia o link para redefinição de senha.
     * 
     * @group Authentication
     * 
     * @bodyParam email string required O e-mail do usuário para o qual será enviado o link de redefinição de senha. Example: user@example.com
     * 
     * @response 200 {
     *  "message": "O link de redefinição de senha foi enviado para seu e-mail.",
     *  "type": "success"
     * }
     */
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);
    
        $user = User::where('email', $request->email)->first();
    
        if ($user) {
            // Gerar o token de redefinição de senha
            $token = app('auth.password.broker')->createToken($user);
    
            // Enviar a notificação personalizada
            $user->notify(new CustomResetPasswordNotification($token));
        }
    
        return response()->json(['message' => 'Link de redefinição de senha enviado para o e-mail.']);
    }

    /**
     * Redefine a senha do usuário.
     * 
     * @group Authentication
     * 
     * @bodyParam token string required O token de redefinição de senha. Example: abc123
     * @bodyParam email string required O e-mail do usuário. Example: user@example.com
     * @bodyParam password string required A nova senha do usuário. Example: "novaSenha123"
     * @bodyParam password_confirmation string required Confirmação da nova senha. Example: "novaSenha123"
     * 
     * @response 200 {
     *  "message": "Senha redefinida com sucesso.",
     *  "type": "success"
     * }
     */
    public function resetPassword(Request $request): JsonResponse
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json([
                'message' => 'Senha redefinida com sucesso.',
                'type' => 'success',
            ], 200);
        }

        return response()->json([
            'message' => 'Erro ao redefinir a senha.',
            'type' => 'error',
        ], 400);
    }
}
