<?php

namespace App\Domain\Auth\ObjectValues;

use InvalidArgumentException;

class Password
{
    private string $value;

    public function __construct(string $password)
    {
        if (strlen($password) < 8) {
            throw new InvalidArgumentException('A senha deve ter pelo menos 8 caracteres.');
        }
        $this->value = $password; 
    }

    public function getValue(): string
    {
        return $this->value;
    }

    // Não é recomendado ter um __toString() para senhas por segurança
}

