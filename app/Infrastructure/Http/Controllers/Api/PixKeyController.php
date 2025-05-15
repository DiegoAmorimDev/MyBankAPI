<?php

namespace App\Infrastructure\Http\Controllers\Api;

use App\Domain\PixKey\Entities\PixKey;
use App\Domain\PixKey\ObjectValues\AccountId;
use App\Domain\PixKey\ObjectValues\KeyType;
use App\Domain\PixKey\ObjectValues\KeyValue;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PixKeyController extends Controller
{
    // Mocked data for demonstration
    private static array $pixKeys = [];

    public function create(Request $request): JsonResponse
    {
        try {
            // Validação básica da requisição
            $validatedData = $request->validate([
                'account_id' => 'required|string',
                'key_type' => 'required|string',
                'key_value' => 'required|string',
            ]);

            $accountId = new AccountId($validatedData['account_id']);
            $keyType = new KeyType($validatedData['key_type']);
            // A validação do KeyValue agora espera um KeyType
            $keyValue = new KeyValue($validatedData['key_value'], $keyType);

            $pixKey = new PixKey($accountId, $keyType, $keyValue);
            self::$pixKeys[$pixKey->getId()] = $pixKey; // Salva em memória

            return response()->json([
                'message' => 'Chave PIX criada com sucesso!',
                'pix_key_id' => $pixKey->getId(),
                'data' => [
                    'account_id' => $pixKey->getAccountId()->getValue(),
                    'key_type' => $pixKey->getKeyType()->getValue(),
                    'key_value' => $pixKey->getKeyValue()->getValue(),
                    'created_at' => $pixKey->getCreatedAt()->format(\DateTimeInterface::ATOM),
                ]
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
        if (!isset(self::$pixKeys[$id])) {
            return response()->json(['error' => 'Chave PIX não encontrada.'], 404);
        }
        $pixKey = self::$pixKeys[$id];
        return response()->json([
            'pix_key_id' => $pixKey->getId(),
            'account_id' => $pixKey->getAccountId()->getValue(),
            'key_type' => $pixKey->getKeyType()->getValue(),
            'key_value' => $pixKey->getKeyValue()->getValue(),
            'created_at' => $pixKey->getCreatedAt()->format(\DateTimeInterface::ATOM),
        ]);
    }

    // Outros métodos como listByAccount, delete, etc. podem ser adicionados aqui
}

