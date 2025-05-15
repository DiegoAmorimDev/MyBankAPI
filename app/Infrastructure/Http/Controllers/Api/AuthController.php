<?php

namespace App\Infrastructure\Http\Controllers\Api; // <--- Alteração aqui

use Illuminate\Http\Request;
use App\Http\Controllers\Controller; // Certifique-se que o Controller base do Laravel está sendo usado corretamente

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'cpf' => 'required|string',
            'password' => 'required|string',
        ]);
        return response()->json([
            'message' => 'Endpoint de login acessado. Dados recebidos.',
            'dados_recebidos' => $credentials
            // aqui deveria retornar um token de acesso.
        ]);
    }
}

