<?php

namespace Infrastructure\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller; // Importando o Controller base do Laravel

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Aqui você implementaria a lógica de autenticação real.
        // Por enquanto, apenas uma resposta de sucesso para teste.
        return response()->json(['message' => 'Rota de login acessada com sucesso!']);
    }
}

