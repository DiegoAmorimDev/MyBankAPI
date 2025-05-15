<?php

namespace App\Domain\PixTransaction\ObjectValues;

use InvalidArgumentException;

class Amount
{
    private float $value;

    public function __construct(float $amount)
    {
        if ($amount <= 0) {
            throw new InvalidArgumentException("O valor da transação deve ser positivo.");
        }
        // Poderia adicionar validação de número de casas decimais (ex: 2 para BRL)
        $this->value = $amount;
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return (string)$this->value;
    }
}

