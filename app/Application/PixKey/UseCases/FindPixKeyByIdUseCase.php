<?php

namespace App\Application\PixKey\UseCases;

use App\Application\PixKey\DTOs\FindPixKeyByIdRequestDTO;
use App\Application\PixKey\DTOs\PixKeyResponseDTO;

/**
 * Caso de uso para buscar uma chave PIX por ID
 */
class FindPixKeyByIdUseCase
{
    // Em um cenário real, injetaríamos um repositório aqui
    private static array $pixKeys = [];

    /**
     * Executa o caso de uso de busca de chave PIX por ID
     * 
     * @param FindPixKeyByIdRequestDTO $requestDTO Dados da requisição
     * @return PixKeyResponseDTO|null Dados da chave PIX encontrada ou null se não encontrada
     */
    public function execute(FindPixKeyByIdRequestDTO $requestDTO): ?PixKeyResponseDTO
    {
        $id = $requestDTO->getId();
        
        // Simular busca no repositório
        // Em um cenário real, usaríamos um repositório injetado
        if (!isset(self::$pixKeys[$id])) {
            return null;
        }
        
        $pixKey = self::$pixKeys[$id];
        
        // Retornar DTO de resposta
        return new PixKeyResponseDTO(
            $pixKey->getId(),
            $pixKey->getAccountId()->getValue(),
            $pixKey->getKeyType()->getValue(),
            $pixKey->getKeyValue()->getValue(),
            $pixKey->getCreatedAt()->format(\DateTimeInterface::ATOM)
        );
    }
}
