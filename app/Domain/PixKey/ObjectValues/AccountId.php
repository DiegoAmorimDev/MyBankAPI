<?php

namespace App\Domain\PixKey\ObjectValues;

use InvalidArgumentException;

class AccountId
{
    private string $value;

    public function __construct(string $accountId)
    {
        // Exemplo de validação: UUID
        // Em um cenário real, a validação pode ser mais específica
        // para o formato do ID da conta utilizado pelo banco.
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

