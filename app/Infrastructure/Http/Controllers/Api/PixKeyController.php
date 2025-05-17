<?php

namespace App\Infrastructure\Http\Controllers\Api;

use App\Application\PixKey\DTOs\CreatePixKeyRequestDTO;
use App\Application\PixKey\DTOs\FindPixKeyByIdRequestDTO;
use App\Application\PixKey\UseCases\CreatePixKeyUseCase;
use App\Application\PixKey\UseCases\FindPixKeyByIdUseCase;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PixKeyController extends Controller
{
    private CreatePixKeyUseCase $createPixKeyUseCase;
    private FindPixKeyByIdUseCase $findPixKeyByIdUseCase;

    public function __construct()
    {
        // Em um cenário real, esses use cases seriam injetados via DI
        $this->createPixKeyUseCase = new CreatePixKeyUseCase();
        $this->findPixKeyByIdUseCase = new FindPixKeyByIdUseCase();
    }

    public function create(Request $request): JsonResponse
    {
        try {
            
            $validatedData = $request->validate([
                'account_id' => 'required|string',
                'key_type' => 'required|string',
                'key_value' => 'required|string',
            ]);

            
            $requestDTO = new CreatePixKeyRequestDTO(
                $validatedData['account_id'],
                $validatedData['key_type'],
                $validatedData['key_value']
            );

            
            $responseDTO = $this->createPixKeyUseCase->execute($requestDTO);

            
            return response()->json([
                'message' => 'Chave PIX criada com sucesso!',
                'pix_key_id' => $responseDTO->getId(),
                'data' => $responseDTO->toArray()
            ], 201);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => 'Dados de entrada inválidos.', 'details' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro interno ao criar chave PIX: ' . $e->getMessage()], 500);
        }
    }

    public function findById(string $id): JsonResponse
    {
        try {
            // Criar DTO de requisição
            $requestDTO = new FindPixKeyByIdRequestDTO($id);

            // Executar caso de uso
            $responseDTO = $this->findPixKeyByIdUseCase->execute($requestDTO);

            // Verificar se a chave foi encontrada
            if ($responseDTO === null) {
                return response()->json(['error' => 'Chave PIX não encontrada.'], 404);
            }

            // Retornar resposta
            return response()->json([
                'message' => 'Chave PIX encontrada com sucesso!',
                'data' => $responseDTO->toArray()
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao buscar chave PIX: ' . $e->getMessage()], 500);
        }
    }

    // Outros métodos como listByAccount, delete, etc. podem ser adicionados aqui
}
