<?php

namespace Infrastructure\Persistence\Mock\Repositories;

use App\Domain\PixTransaction\Entities\PixTransaction;
use App\Domain\PixTransaction\ObjectValues\Amount;
use App\Domain\PixTransaction\ObjectValues\Description;
use App\Domain\PixTransaction\ObjectValues\Timestamp;
use App\Domain\PixTransaction\Repositories\PixTransactionRepositoryInterface;
use App\Domain\PixKey\ObjectValues\AccountId;
use Domain\Auth\Repositories\UserRepositoryInterface;

class PixTransactionMockRepository implements PixTransactionRepositoryInterface
{
    private array $transactions = [];
    private array $accountBalances = [];
    private ?UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository = null)
    {
        $this->userRepository = $userRepository;
        
        // Inicializa saldos mockados para contas
        $this->accountBalances['550e8400-e29b-41d4-a716-446655440000'] = 1250.75; // Conta do usuário 1
        $this->accountBalances['550e8400-e29b-41d4-a716-446655440001'] = 3500.50; // Conta do usuário 2
        
        // Inicializa algumas transações mockadas
        $transaction1 = new PixTransaction(
            new AccountId('550e8400-e29b-41d4-a716-446655440000'),
            new AccountId('550e8400-e29b-41d4-a716-446655440001'),
            new Amount(100.00),
            new Description('Pagamento de conta')
        );
        $transaction1->complete();
        
        $transaction2 = new PixTransaction(
            new AccountId('550e8400-e29b-41d4-a716-446655440001'),
            new AccountId('550e8400-e29b-41d4-a716-446655440000'),
            new Amount(50.00),
            new Description('Devolução parcial')
        );
        $transaction2->complete();
        
        // Armazena transações no array
        $this->transactions[$transaction1->getId()->getValue()] = $transaction1;
        $this->transactions[$transaction2->getId()->getValue()] = $transaction2;
    }

    public function find(string $id): ?PixTransaction
    {
        return $this->transactions[$id] ?? null;
    }
    
    public function findByAccountId(string $accountId): array
    {
        $result = [];
        
        foreach ($this->transactions as $transaction) {
            if ($transaction->getPayerAccountId()->getValue() === $accountId || 
                $transaction->getPayeeAccountId()->getValue() === $accountId) {
                $result[] = $transaction;
            }
        }
        
        return $result;
    }
    
    public function save(PixTransaction $pixTransaction): PixTransaction
    {
        $this->transactions[$pixTransaction->getId()->getValue()] = $pixTransaction;
        return $pixTransaction;
    }
    
    public function update(PixTransaction $pixTransaction): PixTransaction
    {
        $this->transactions[$pixTransaction->getId()->getValue()] = $pixTransaction;
        return $pixTransaction;
    }
    
    public function transferFunds(string $sourceAccountId, string $destinationAccountId, float $amount): bool
    {
        // Verifica se as contas existem
        if (!isset($this->accountBalances[$sourceAccountId]) || !isset($this->accountBalances[$destinationAccountId])) {
            return false;
        }
        
        // Verifica se há saldo suficiente
        if ($this->accountBalances[$sourceAccountId] < $amount) {
            return false;
        }
        
        // Realiza a transferência
        $this->accountBalances[$sourceAccountId] -= $amount;
        $this->accountBalances[$destinationAccountId] += $amount;
        
        // Cria e salva a transação
        $transaction = new PixTransaction(
            new AccountId($sourceAccountId),
            new AccountId($destinationAccountId),
            new Amount($amount),
            new Description('Transferência PIX')
        );
        $transaction->complete();
        
        $this->save($transaction);
        
        return true;
    }
    
    public function getAccountBalance(string $accountId): float
    {
        return $this->accountBalances[$accountId] ?? 0.0;
    }
    
    public function getUserAccountId(int $userId): ?string
    {
        // Mapeamento mockado de IDs de usuário para IDs de conta
        $userAccountMap = [
            1 => '550e8400-e29b-41d4-a716-446655440000',
            2 => '550e8400-e29b-41d4-a716-446655440001'
        ];
        
        return $userAccountMap[$userId] ?? null;
    }
}
