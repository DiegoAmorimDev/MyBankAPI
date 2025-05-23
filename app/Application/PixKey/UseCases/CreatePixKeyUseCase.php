<?php

namespace App\Application\PixKey\UseCases;

use App\Application\PixKey\DTOs\CreatePixKeyRequestDTO;
use App\Application\PixKey\DTOs\PixKeyResponseDTO;
use App\Domain\PixKey\Entities\PixKey;
use App\Domain\PixKey\ObjectValues\AccountId;
use App\Domain\PixKey\ObjectValues\KeyType;
use App\Domain\PixKey\ObjectValues\KeyValue;
use InvalidArgumentException;

/**
 * Caso de uso para criar uma nova chave PIX
 */
class CreatePixKeyUseCase
{
    
    private static array $pixKeys = [];

    /**
     * Executa o caso de uso de criação de chave PIX
     * 
     * @param CreatePixKeyRequestDTO $requestDTO Dados da requisição
     * @return PixKeyResponseDTO Dados da chave PIX criada
     * @throws InvalidArgumentException Se os dados forem inválidos
     */
    public function execute(CreatePixKeyRequestDTO $requestDTO): PixKeyResponseDTO
    {
        
        $accountId = new AccountId($requestDTO->getAccountId());
        $keyType = new KeyType($requestDTO->getKeyType());
        $keyValue = new KeyValue($requestDTO->getKeyValue(), $keyType);

        
        $pixKey = new PixKey($accountId, $keyType, $keyValue);

        
        self::$pixKeys[$pixKey->getId()] = $pixKey;

        
        return new PixKeyResponseDTO(
            $pixKey->getId(),
            $pixKey->getAccountId()->getValue(),
            $pixKey->getKeyType()->getValue(),
            $pixKey->getKeyValue()->getValue(),
            $pixKey->getCreatedAt()->format(\DateTimeInterface::ATOM)
        );
    }
}
