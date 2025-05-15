<?php

namespace App\Infrastructure\Http\Controllers\Api;

use App\Domain\PixKey\ObjectValues\AccountId;
use App\Domain\PixTransaction\Entities\PixTransaction;
use App\Domain\PixTransaction\ObjectValues\Amount;
use App\Domain\PixTransaction\ObjectValues\Description;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PixTransactionController extends Controller
{
    // Mocked data for demonstration
    private static array $pixTransactions = [];

    public function create(Request $request): JsonResponse
    {
        try {
            $validatedData = $request->validate([
                'payer_account_id' => 'required|string',
                'payee_account_id' => 'required|string',
                'amount' => 'required|numeric|gt:0',
                'description' => 'nullable|string|max:255',
            ]);

            $payerAccountId = new AccountId($validatedData['payer_account_id']);
            $payeeAccountId = new AccountId($validatedData['payee_account_id']);
            $amount = new Amount((float)$validatedData['amount']);
            $description = isset($validatedData['description']) ? new Description($validatedData['description']) : null;

            $transaction = new PixTransaction(
                $payerAccountId,
                $payeeAccountId,
                $amount,
                $description
            );

            self::$pixTransactions[$transaction->getId()->getValue()] = $transaction;


            $transaction->complete();
            self::$pixTransactions[$transaction->getId()->getValue()] = $transaction; 

            return response()->json([
                'message' => 'Transação PIX criada e processada com sucesso!',
                'transaction_id' => $transaction->getId()->getValue(),
                'status' => $transaction->getStatus(),
                'data' => [
                    'payer_account_id' => $transaction->getPayerAccountId()->getValue(),
                    'payee_account_id' => $transaction->getPayeeAccountId()->getValue(),
                    'amount' => $transaction->getAmount()->getValue(),
                    'description' => $transaction->getDescription()?->getValue(),
                    'created_at' => $transaction->getCreatedAt()->__toString(),
                    'updated_at' => $transaction->getUpdatedAt()->__toString(),
                ]
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
        if (!isset(self::$pixTransactions[$id])) {
            return response()->json(['error' => 'Transação PIX não encontrada.'], 404);
        }
        $transaction = self::$pixTransactions[$id];
        return response()->json([
            'transaction_id' => $transaction->getId()->getValue(),
            'payer_account_id' => $transaction->getPayerAccountId()->getValue(),
            'payee_account_id' => $transaction->getPayeeAccountId()->getValue(),
            'amount' => $transaction->getAmount()->getValue(),
            'description' => $transaction->getDescription()?->getValue(),
            'status' => $transaction->getStatus(),
            'created_at' => $transaction->getCreatedAt()->__toString(),
            'updated_at' => $transaction->getUpdatedAt()->__toString(),
        ]);
    }

}

