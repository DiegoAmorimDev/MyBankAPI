<?php

namespace App\Domain\PixTransaction\ObjectValues;

use Ramsey\Uuid\Uuid;

class TransactionId
{
    private string $value;

    public function __construct(?string $transactionId = null)
    {
        // usando toString para gerar um uuid v4 se nenhum id for fornecido.
        $this->value = $transactionId ?? Uuid::uuid4()->toString();
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}

