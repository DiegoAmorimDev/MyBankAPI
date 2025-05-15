<?php

namespace App\Domain\PixKey\ObjectValues;

use InvalidArgumentException;

class KeyType
{
    private string $value;
    private const ALLOWED_TYPES = ['cpf', 'cnpj', 'email', 'phone', 'random'];

    public function __construct(string $type)
    {
        $type = strtolower($type);
        if (!in_array($type, self::ALLOWED_TYPES)) {
            throw new InvalidArgumentException('Tipo de chave PIX inválido: ' . $type . '. Tipos permitidos: ' . implode(', ', self::ALLOWED_TYPES));
        }
        $this->value = $type;
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

