<?php

namespace App\Application\PixKey\DTOs;

/**
 * DTO para encapsular os dados de resposta de uma chave PIX
 */
class PixKeyResponseDTO
{
    private string $id;
    private string $accountId;
    private string $keyType;
    private string $keyValue;
    private string $createdAt;

    public function __construct(
        string $id,
        string $accountId,
        string $keyType,
        string $keyValue,
        string $createdAt
    ) {
        $this->id = $id;
        $this->accountId = $accountId;
        $this->keyType = $keyType;
        $this->keyValue = $keyValue;
        $this->createdAt = $createdAt;
    }

    public function getId(): string
    {
        return $this->id;
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

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    /**
     * Converte o DTO para um array associativo
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'account_id' => $this->accountId,
            'key_type' => $this->keyType,
            'key_value' => $this->keyValue,
            'created_at' => $this->createdAt,
        ];
    }
}
