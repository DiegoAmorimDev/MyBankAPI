<?php

namespace App\Application\PixKey\DTOs;

/**
 * DTO para encapsular os dados de entrada para buscar uma chave PIX por ID
 */
class FindPixKeyByIdRequestDTO
{
    private string $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
    }
}
