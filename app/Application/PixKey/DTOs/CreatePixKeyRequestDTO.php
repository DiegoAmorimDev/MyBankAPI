<?php

namespace App\Application\PixKey\DTOs;

/**
 * DTO para encapsular os dados de entrada para criação de uma chave PIX
 */
class CreatePixKeyRequestDTO
{
    private string $accountId;
    private string $keyType;
    private string $keyValue;

    public function __construct(
        string $accountId,
        string $keyType,
        string $keyValue
    ) {
        $this->accountId = $accountId;
        $this->keyType = $keyType;
        $this->keyValue = $keyValue;
    }

    public function getAccountId(): string
    {
        return $this->accountId;
    }

    public function getKeyType(): string
    {
        return $this->keyType;
    }

    public function getKeyValue(): string
    {
        return $this->keyValue;
    }
}
