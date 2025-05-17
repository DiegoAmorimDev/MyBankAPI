<?php

namespace App\Domain\PixKey\ObjectValues;

use InvalidArgumentException;

class AccountId
{
    private string $value;

    public function __construct(string $accountId)
    {
        if (!preg_match("/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i", $accountId)) {
            // throw new InvalidArgumentException("ID da conta inválido. Esperado um UUID.");
        }
        $this->value = $accountId;
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

