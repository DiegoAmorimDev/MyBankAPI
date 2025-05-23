<?php

namespace App\Infrastructure\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Infrastructure\Persistence\Mock\Repositories\UserMockRepository;
use Infrastructure\Persistence\Mock\Repositories\PixTransactionMockRepository;

class AccountController extends Controller
{
    private UserMockRepository $userRepository;
    private PixTransactionMockRepository $pixTransactionRepository;

    public function __construct(UserMockRepository $userRepository, PixTransactionMockRepository $pixTransactionRepository)
    {
        $this->userRepository = $userRepository;
        $this->pixTransactionRepository = $pixTransactionRepository;
    }

    public function getBalance(Request $request)
    {
        // Validação dos dados de entrada
        $request->validate([
            'cpf' => 'required|string',
            'password' => 'required|string',
        ]);

        // Autenticação do usuário
        $user = $this->userRepository->authenticate($request->cpf, $request->password);
        
        if (!$user) {
            return response()->json([
                'message' => 'Credenciais inválidas',
                'status' => 'error'
            ], 401);
        }

        // Obtenção do saldo
        $balance = $this->userRepository->getBalance($user->id);
        
        return response()->json([
            'message' => 'Consulta de saldo realizada com sucesso',
            'data' => [
                'user_id' => $user->id,
                'name' => $user->name,
                'balance' => $balance,
                'currency' => 'BRL'
            ],
            'status' => 'success'
        ]);
    }
}
