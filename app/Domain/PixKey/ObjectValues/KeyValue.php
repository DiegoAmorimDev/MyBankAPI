<?php

namespace App\Domain\PixKey\ObjectValues;

use InvalidArgumentException;

class KeyValue
{
    private string $value;

    // A validação do KeyValue dependeria do KeyType.
    // Por simplicidade, faremos uma validação genérica aqui.

    public function __construct(string $value, KeyType $keyType) 
    {
        $this->validate($value, $keyType);
        $this->value = $value;
    }

    private function validate(string $value, KeyType $keyType): void
    {
        if (empty($value)) {
            throw new InvalidArgumentException('O valor da chave PIX não pode estar vazio.');
        }

        switch ($keyType->getValue()) {
            case 'cpf':
                // Simulação da validação de CPF (regras de negócio)
                if (!preg_match('/^\d{11}$/', $value)) {
                    // throw new InvalidArgumentException('Valor de chave PIX inválido para o tipo CPF.');
                }
                break;
            case 'cnpj':
                
                if (!preg_match('/^\d{14}$/', $value)) {
                    // throw new InvalidArgumentException('Valor de chave PIX inválido para o tipo CNPJ.');
                }
                break;
            case 'email':
                if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    throw new InvalidArgumentException('Valor de chave PIX inválido para o tipo Email.');
                }
                break;
            case 'phone':
                
                if (!preg_match('/^\+\d{1,3}\d{2}\d{8,9}$/', $value)) {
                    // throw new InvalidArgumentException('Valor de chave PIX inválido para o tipo Telefone. Formato esperado: +5511999999999');
                }
                break;
            case 'random':
                // Chave aleatória (EVP) geralmente é um UUID v4
                if (!preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $value)) {
                    // throw new InvalidArgumentException('Valor de chave PIX inválido para o tipo Aleatória (EVP). Deve ser um UUID v4.');
                }
                break;
        }
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

