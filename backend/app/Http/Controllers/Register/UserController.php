<?php

namespace App\Http\Controllers\Register;

use App\Http\Controllers\Controller;
use App\Models\Register\User;
use App\Http\Requests\Register\StoreUserRequest;
use App\Http\Resources\Register\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UserController extends Controller
{
    /**
     * List all users.
     * 
     * @group User Management
     * 
     * @response 200 {
     *  "data": [
     *    {
     *      "id": 1,
     *      "name": "John Doe",
     *      "email": "johndoe@example.com"
     *    }
     *  ],
     *  "message": {
     *    "text": "Lista de usuários recuperada com sucesso.",
     *    "type": "info"
     *  }
     * }
     */
    public function index(): ResourceCollection
    {
            return UserResource::collection(User::all())
            ->additional([
                'message' => [
                    'text' => 'Lista de usuários recuperada com sucesso.',
                    'type' => 'info',
                ],
            ]);
    }

    /**
     * Create a new user.
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
     *    "text": "Usuário criado com sucesso.",
     *    "type": "success"
     *  }
     * }
     */
    public function store(StoreUserRequest $request): JsonResource
    {
        $user = User::create($request->validated());
        return UserResource::make($user)
            ->additional([
                'message' => [
                    'text' => 'Usuário criado com sucesso.',
                    'type' => 'success',
                ],
            ], 201);
    }

    /**
     * Update an existing user.
     * 
     * @group User Management
     * 
     * @urlParam id int required The ID of the user. Example: 1
     * @bodyParam name string The updated name of the user. Example: John Doe
     * @bodyParam email string The updated email of the user. Example: johndoe@example.com
     * @bodyParam password string The updated password of the user. Example: newpassword
     * 
     * @response 200 {
     *  "data": {
     *    "id": 1,
     *    "name": "John Doe",
     *    "email": "johndoe@example.com"
     *  },
     *  "message": {
     *    "text": "Usuário atualizado com sucesso.",
     *    "type": "success"
     *  }
     * }
     */
    public function update(StoreUserRequest $request, int $id): JsonResource
    {
        $user = User::findOrFail($id);
        $user->update(array_filter($request->validated()));
        return UserResource::make($user)
            ->additional([
                'message' => [
                    'text' => 'Usuário atualizado com sucesso.',
                    'type' => 'success',
                ],
            ], 200);
    }

    /**
     * Delete a user.
     * 
     * @group User Management
     * 
     * @urlParam id int required The ID of the user. Example: 1
     * 
     * @response 204 {
     *  "message": {
     *    "text": "Usuário excluído com sucesso.",
     *    "type": "success"
     *  }
     * }
     */
    public function destroy(int $id): JsonResource
    {
        $user = User::findOrFail($id);
        $user->delete();
        return (new JsonResource(null))
            ->additional([
                'message' => [
                    'text' => 'Usuário excluído com sucesso.',
                    'type' => 'success',
                ],
            ], 204);
    }
}
