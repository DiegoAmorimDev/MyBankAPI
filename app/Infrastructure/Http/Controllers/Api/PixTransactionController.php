<?php

namespace App\Infrastructure\Http\Controllers\Api;

use App\Application\PixTransaction\DTOs\CreatePixTransactionRequestDTO;
use App\Application\PixTransaction\DTOs\FindPixTransactionByIdRequestDTO;
use App\Application\PixTransaction\UseCases\CreatePixTransactionUseCase;
use App\Application\PixTransaction\UseCases\FindPixTransactionByIdUseCase;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PixTransactionController extends Controller
{
    private CreatePixTransactionUseCase $createPixTransactionUseCase;
    private FindPixTransactionByIdUseCase $findPixTransactionByIdUseCase;

    public function __construct()
    {
        
        $this->createPixTransactionUseCase = new CreatePixTransactionUseCase();
        $this->findPixTransactionByIdUseCase = new FindPixTransactionByIdUseCase();
    }

    public function create(Request $request): JsonResponse
    {
        try {
            
            $validatedData = $request->validate([
                'payer_account_id' => 'required|string',
                'payee_account_id' => 'required|string',
                'amount' => 'required|numeric|gt:0',
                'description' => 'nullable|string|max:255',
            ]);

            
            $requestDTO = new CreatePixTransactionRequestDTO(
                $validatedData['payer_account_id'],
                $validatedData['payee_account_id'],
                (float)$validatedData['amount'],
                $validatedData['description'] ?? null
            );

            
            $responseDTO = $this->createPixTransactionUseCase->execute($requestDTO);

            
            return response()->json([
                'message' => 'Transação PIX criada e processada com sucesso!',
                'transaction_id' => $responseDTO->getId(),
                'status' => $responseDTO->getStatus(),
                'data' => $responseDTO->toArray()
            ], 201);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => 'Dados de entrada inválidos.', 'details' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro interno ao processar transação PIX: ' . $e->getMessage()], 500);
        }
    }

    public function findById(string $id): JsonResponse
    {
        try {
            
            $requestDTO = new FindPixTransactionByIdRequestDTO($id);

            
            $responseDTO = $this->findPixTransactionByIdUseCase->execute($requestDTO);

            
            if ($responseDTO === null) {
                return response()->json(['error' => 'Transação PIX não encontrada.'], 404);
            }

            
            return response()->json([
                'message' => 'Transação PIX encontrada com sucesso!',
                'data' => $responseDTO->toArray()
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao buscar transação PIX: ' . $e->getMessage()], 500);
        }
    }
}
