<?php

namespace App\Application\PixTransaction\DTOs;

/**
 * DTO para encapsular os dados de entrada para criação de uma transação PIX
 */
class CreatePixTransactionRequestDTO
{
    private string $payerAccountId;
    private string $payeeAccountId;
    private float $amount;
    private ?string $description;

    public function __construct(
        string $payerAccountId,
        string $payeeAccountId,
        float $amount,
        ?string $description = null
    ) {
        $this->payerAccountId = $payerAccountId;
        $this->payeeAccountId = $payeeAccountId;
        $this->amount = $amount;
        $this->description = $description;
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
}
