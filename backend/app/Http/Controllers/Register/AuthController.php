<?php

namespace App\Http\Controllers\Register;

use App\Http\Controllers\Controller;
use App\Services\Register\AuthService;
use App\Services\Register\UserRegistrationService;
use App\Http\Requests\Register\LoginRequest;
use App\Http\Requests\Register\RegisterUserRequest;
use App\Http\Resources\Register\UserResource;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function __construct(
        private AuthService $authService,
        private UserRegistrationService $userRegistrationService
    ) {}

    /**
     * Register a new user.
     * 
     * @group User Management
     * 
     * @bodyParam name string required The name of the user. Example: John Doe
     * @bodyParam email string required The email of the user. Example: johndoe@example.com
     * @bodyParam password string required The password of the user. Example: secret
     * 
     * @response 201 {
     *  "data": {
     *    "id": 1,
     *    "name": "John Doe",
     *    "email": "johndoe@example.com"
     *  },
     *  "message": {
     *    "text": "Registro realizado com sucesso! Verifique seu e-mail para ativar sua conta.",
     *    "type": "success"
     *  }
     * }
     */
    public function register(RegisterUserRequest $request): JsonResponse
    {
        $user = $this->userRegistrationService->registerUserWithCompany($request->validated());
        try {
            $this->userRegistrationService->sendActivationEmail($user);
        } catch (\Exception $e) {
            \Log::error('Erro ao enviar e-mail de ativação:', ['error' => $e->getMessage()]);
        }

        return response()->json([
            'user' => UserResource::make($user),
            'message' => [
                'text' => 'Registro realizado com sucesso! Verifique seu e-mail para ativar sua conta.',
                'type' => 'success',
            ],
        ], 201);
    }

    /**
     * Activate user account.
     * 
     * @group User Management
     * 
     * @urlParam token string required The activation token of the user. Example: abc123
     * 
     * @response 200 {
     *  "message": {
     *    "text": "Conta ativada com sucesso! Um e-mail de boas-vindas foi enviado.",
     *    "type": "success"
     *  }
     * }
     */
    public function activateAccount($token): JsonResponse
    {
        $response = $this->userRegistrationService->activateUserAccount($token);

        return response()->json($response, $response['status']);
    }

    /**
     * Login a user.
     * 
     * @group User Management
     * 
     * @bodyParam email string required The email of the user. Example: johndoe@example.com
     * @bodyParam password string required The password of the user. Example: secret
     * 
     * @response 200 {
     *  "data": {
     *    "id": 1,
     *    "name": "John Doe",
     *    "email": "johndoe@example.com"
     *  },
     *  "access_token": "Bearer <token>",
     *  "token_type": "Bearer",
     *  "message": {
     *    "text": "Login realizado com sucesso!",
     *    "type": "success"
     *  }
     * }
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $authData = $this->authService->authenticate($request->validated());        

        return response()->json([
            'user' => UserResource::make($authData),
            'access_token' => $authData->createToken('auth_token')->plainTextToken,
            'token_type' => 'Bearer',
            'message' => [
                'text' => 'Login realizado com sucesso!',
                'type' => 'success',
            ],
        ], 200);
    }

    /**
     * Logout a user.
     * 
     * @group User Management
     * 
     * @response 200 {
     *  "message": {
     *    "text": "Logout realizado com sucesso!",
     *    "type": "success"
     *  }
     * }
     */
    public function logout(Request $request): JsonResponse
    {
        $this->authService->logout($request->user());

        return response()->json([
            'message' => [
                'text' => 'Logout realizado com sucesso!',
                'type' => 'success',
            ],
        ], 200);
    }
}
