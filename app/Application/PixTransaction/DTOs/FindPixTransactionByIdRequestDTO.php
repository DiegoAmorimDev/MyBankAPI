<?php

namespace App\Application\PixTransaction\DTOs;

/**
 * DTO para encapsular os dados de entrada para buscar uma transação PIX por ID
 */
class FindPixTransactionByIdRequestDTO
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
