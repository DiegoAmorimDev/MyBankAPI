<?php

namespace App\Infrastructure\Http\Controllers\Api;

use App\Application\PixTransaction\DTOs\CreatePixTransactionRequestDTO;
use App\Application\PixTransaction\DTOs\FindPixTransactionByIdRequestDTO;
use App\Application\PixTransaction\UseCases\CreatePixTransactionUseCase;
use App\Application\PixTransaction\UseCases\FindPixTransactionByIdUseCase;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Infrastructure\Persistence\Mock\Repositories\PixKeyMockRepository;
use Infrastructure\Persistence\Mock\Repositories\PixTransactionMockRepository;
use Infrastructure\Persistence\Mock\Repositories\UserMockRepository;

class PixTransactionController extends Controller
{
    private CreatePixTransactionUseCase $createPixTransactionUseCase;
    private FindPixTransactionByIdUseCase $findPixTransactionByIdUseCase;
    private PixKeyMockRepository $pixKeyRepository;
    private PixTransactionMockRepository $pixTransactionRepository;
    private UserMockRepository $userRepository;

    public function __construct(
        PixKeyMockRepository $pixKeyRepository = null, 
        PixTransactionMockRepository $pixTransactionRepository = null,
        UserMockRepository $userRepository = null
    ) {
        $this->userRepository = $userRepository ?? new UserMockRepository();
        $this->pixKeyRepository = $pixKeyRepository ?? new PixKeyMockRepository();
        $this->pixTransactionRepository = $pixTransactionRepository ?? new PixTransactionMockRepository($this->userRepository);
        $this->createPixTransactionUseCase = new CreatePixTransactionUseCase();
        $this->findPixTransactionByIdUseCase = new FindPixTransactionByIdUseCase();
    }

    public function create(Request $request): JsonResponse
    {
        try {
            // Validação dos dados de entrada
            $validatedData = $request->validate([
                'payer_account_id' => 'required|string',
                'payee_key_value' => 'required_without:payee_account_id|string',
                'payee_account_id' => 'required_without:payee_key_value|string',
                'amount' => 'required|numeric|gt:0',
                'description' => 'nullable|string|max:255',
            ]);

            $payeeAccountId = null;
            
            // Se foi fornecida uma chave PIX, buscar a conta associada
            if (isset($validatedData['payee_key_value'])) {
                $pixKey = $this->pixKeyRepository->findByValue($validatedData['payee_key_value']);
                if (!$pixKey) {
                    return response()->json([
                        'error' => 'Chave PIX não encontrada nos dados mockados.',
                        'status' => 'error'
                    ], 404);
                }
                
                $payeeAccountId = $pixKey->getAccountId()->getValue();
            } 
            // Se foi fornecido o ID da conta diretamente
            elseif (isset($validatedData['payee_account_id'])) {
                $payeeAccountId = $validatedData['payee_account_id'];
            }
            // Caso improvável, mas para garantir
            else {
                return response()->json([
                    'error' => 'É necessário fornecer uma chave PIX ou ID da conta do destinatário.',
                    'status' => 'error'
                ], 422);
            }
            
            // Verificar se o account_id do pagador existe nos dados mockados
            $payerAccountExists = false;
            $accountMap = [
                '550e8400-e29b-41d4-a716-446655440000' => 1, // Conta do usuário 1
                '550e8400-e29b-41d4-a716-446655440001' => 2  // Conta do usuário 2
            ];
            
            foreach ($accountMap as $accountId => $userId) {
                if ($accountId === $validatedData['payer_account_id']) {
                    $payerAccountExists = true;
                    break;
                }
            }
            
            if (!$payerAccountExists) {
                return response()->json([
                    'error' => 'Conta do pagador não encontrada nos dados mockados.',
                    'status' => 'error'
                ], 404);
            }
            
            // Realizar a transferência usando o repositório mock
            $success = $this->pixTransactionRepository->transferFunds(
                $validatedData['payer_account_id'],
                $payeeAccountId,
                (float)$validatedData['amount']
            );
            
            if (!$success) {
                return response()->json([
                    'error' => 'Não foi possível realizar a transferência. Verifique o saldo ou os dados informados.',
                    'status' => 'error'
                ], 400);
            }
            
            // Criar DTO para o caso de uso
            $requestDTO = new CreatePixTransactionRequestDTO(
                $validatedData['payer_account_id'],
                $payeeAccountId,
                (float)$validatedData['amount'],
                $validatedData['description'] ?? null
            );

            // Executar o caso de uso
            $responseDTO = $this->createPixTransactionUseCase->execute($requestDTO);

            // Retornar resposta de sucesso
            return response()->json([
                'message' => 'Transação PIX criada e processada com sucesso!',
                'transaction_id' => $responseDTO->getId(),
                'status' => $responseDTO->getStatus(),
                'data' => [
                    'id' => $responseDTO->getId(),
                    'payer_account_id' => $validatedData['payer_account_id'],
                    'payee_account_id' => $payeeAccountId,
                    'payee_key_value' => $validatedData['payee_key_value'] ?? null,
                    'amount' => (float)$validatedData['amount'],
                    'description' => $validatedData['description'] ?? null,
                    'status' => 'COMPLETED',
                    'created_at' => $responseDTO->getCreatedAt(),
                    'updated_at' => $responseDTO->getUpdatedAt()
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
        try {
            // Criar DTO de requisição
            $requestDTO = new FindPixTransactionByIdRequestDTO($id);

            // Executar o caso de uso
            $responseDTO = $this->findPixTransactionByIdUseCase->execute($requestDTO);

            // Verificar se a transação foi encontrada
            if ($responseDTO === null) {
                return response()->json(['error' => 'Transação PIX não encontrada.'], 404);
            }

            // Retornar resposta de sucesso
            return response()->json([
                'message' => 'Transação PIX encontrada com sucesso!',
                'data' => $responseDTO->toArray()
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao buscar transação PIX: ' . $e->getMessage()], 500);
        }
    }
}
