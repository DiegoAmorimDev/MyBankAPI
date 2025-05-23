<?php

namespace App\Infrastructure\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Infrastructure\Persistence\Mock\Repositories\UserMockRepository;

class AuthController extends Controller
{
    private UserMockRepository $userRepository;

    public function __construct(UserMockRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'cpf' => 'required|string',
            'password' => 'required|string',
        ]);

        // Autenticação do usuário usando o repositório mock
        $user = $this->userRepository->authenticate($credentials['cpf'], $credentials['password']);
        
        if (!$user) {
            return response()->json([
                'message' => 'Credenciais inválidas',
                'status' => 'error'
            ], 401);
        }

        return response()->json([
            'message' => 'Login realizado com sucesso',
            'data' => [
                'user_id' => $user->id,
                'name' => $user->name,
                'token' => 'mock_token_' . $user->id . '_' . time() // Token mockado
            ],
            'status' => 'success'
        ]);
    }
}
