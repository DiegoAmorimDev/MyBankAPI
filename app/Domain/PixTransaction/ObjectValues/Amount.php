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

