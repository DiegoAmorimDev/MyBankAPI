<?php

namespace App\Application\PixTransaction\UseCases;

use App\Application\PixTransaction\DTOs\FindPixTransactionByIdRequestDTO;
use App\Application\PixTransaction\DTOs\PixTransactionResponseDTO;

/**
 * Caso de uso para buscar uma transação PIX por ID
 */
class FindPixTransactionByIdUseCase
{
  
    private static array $pixTransactions = [];

    /**
     * Executa o caso de uso de busca de transação PIX por ID
     * 
     * @param FindPixTransactionByIdRequestDTO 
     * @return PixTransactionResponseDTO|null 
     */
    public function execute(FindPixTransactionByIdRequestDTO $requestDTO): ?PixTransactionResponseDTO
    {
        $id = $requestDTO->getId();
        
        // Simular persistência
        if (!isset(self::$pixTransactions[$id])) {
            return null;
        }
        
        $transaction = self::$pixTransactions[$id];
        

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
