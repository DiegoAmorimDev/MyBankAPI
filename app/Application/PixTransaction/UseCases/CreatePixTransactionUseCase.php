<?php

namespace App\Application\PixTransaction\UseCases;

use App\Application\PixTransaction\DTOs\CreatePixTransactionRequestDTO;
use App\Application\PixTransaction\DTOs\PixTransactionResponseDTO;
use App\Domain\PixKey\ObjectValues\AccountId;
use App\Domain\PixTransaction\Entities\PixTransaction;
use App\Domain\PixTransaction\ObjectValues\Amount;
use App\Domain\PixTransaction\ObjectValues\Description;
use InvalidArgumentException;

/**
 * Caso de uso para criar uma nova transação PIX
 */
class CreatePixTransactionUseCase
{
    
    private static array $pixTransactions = [];

    /**
     * Executa o caso de uso de criação de transação PIX
     * 
     * @param CreatePixTransactionRequestDTO 
     * @return PixTransactionResponseDTO 
     * @throws InvalidArgumentException 
     */
    public function execute(CreatePixTransactionRequestDTO $requestDTO): PixTransactionResponseDTO
    {

        $payerAccountId = new AccountId($requestDTO->getPayerAccountId());
        $payeeAccountId = new AccountId($requestDTO->getPayeeAccountId());
        $amount = new Amount((float)$requestDTO->getAmount());
        

        $description = $requestDTO->getDescription() !== null ? 
            new Description($requestDTO->getDescription()) : null;

      
        $transaction = new PixTransaction(
            $payerAccountId,
            $payeeAccountId,
            $amount,
            $description
        );

        // Simular persistência 
        self::$pixTransactions[$transaction->getId()->getValue()] = $transaction;

        
        $transaction->complete();
        
        
        self::$pixTransactions[$transaction->getId()->getValue()] = $transaction;

        
        return new PixTransactionResponseDTO(
            $transaction->getId()->getValue(),
            $transaction->getPayerAccountId()->getValue(),
            $transaction->getPayeeAccountId()->getValue(),
            $transaction->getAmount()->getValue(),
            $transaction->getStatus(),
            $transaction->getCreatedAt()->__toString(),
            $transaction->getUpdatedAt()->__toString(),
            $transaction->getDescription()?->getValue()
        );
    }
}
