<?php

namespace App\Application\PixTransaction\DTOs;

/**
 * DTO para encapsular os dados de resposta de uma transação PIX
 */
class PixTransactionResponseDTO
{
    private string $id;
    private string $payerAccountId;
    private string $payeeAccountId;
    private float $amount;
    private ?string $description;
    private string $status;
    private string $createdAt;
    private string $updatedAt;

    public function __construct(
        string $id,
        string $payerAccountId,
        string $payeeAccountId,
        float $amount,
        string $status,
        string $createdAt,
        string $updatedAt,
        ?string $description = null
    ) {
        $this->id = $id;
        $this->payerAccountId = $payerAccountId;
        $this->payeeAccountId = $payeeAccountId;
        $this->amount = $amount;
        $this->description = $description;
        $this->status = $status;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getPayerAccountId(): string
    {
        return $this->payerAccountId;
    }

    public function getPayeeAccountId(): string
    {
        return $this->payeeAccountId;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }

    /**
     * Converte o DTO para um array associativo
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'payer_account_id' => $this->payerAccountId,
            'payee_account_id' => $this->payeeAccountId,
            'amount' => $this->amount,
            'description' => $this->description,
            'status' => $this->status,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
        ];
    }
}
