<?php

namespace App\Domain\PixTransaction\ObjectValues;

use InvalidArgumentException;

class Description
{
    private string $value;

    public function __construct(string $description)
    {
        // Exemplo de validação: Limitar o tamanho da descrição
        if (strlen($description) > 255) {
            throw new InvalidArgumentException("A descrição da transação não pode exceder 255 caracteres.");
        }
        if (empty($description)) {
            throw new InvalidArgumentException("A descrição não pode ser vazia, caso fornecida.");
        }
        $this->value = $description;
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

