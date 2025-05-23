<?php

namespace Infrastructure\Persistence\Mock\Repositories;

use App\Domain\PixKey\Entities\PixKey;
use App\Domain\PixKey\ObjectValues\AccountId;
use App\Domain\PixKey\ObjectValues\KeyType;
use App\Domain\PixKey\ObjectValues\KeyValue;
use App\Domain\PixKey\Repositories\PixKeyRepositoryInterface;

class PixKeyMockRepository implements PixKeyRepositoryInterface
{
    private array $pixKeys = [];

    public function __construct()
    {
        // Inicializa chaves PIX mockadas para o usuário 1
        $accountId1 = new AccountId('550e8400-e29b-41d4-a716-446655440000');
        
        $pixKey1 = new PixKey(
            $accountId1,
            new KeyType('cpf'),
            new KeyValue('12345678900', new KeyType('cpf'))
        );
        
        $pixKey2 = new PixKey(
            $accountId1,
            new KeyType('email'),
            new KeyValue('joao@example.com', new KeyType('email'))
        );
        
        // Inicializa chaves PIX mockadas para o usuário 2
        $accountId2 = new AccountId('550e8400-e29b-41d4-a716-446655440001');
        
        $pixKey3 = new PixKey(
            $accountId2,
            new KeyType('cpf'),
            new KeyValue('98765432100', new KeyType('cpf'))
        );
        
        $pixKey4 = new PixKey(
            $accountId2,
            new KeyType('phone'),
            new KeyValue('+5511999999999', new KeyType('phone'))
        );
        
        // Armazena chaves PIX no array
        $this->pixKeys[$pixKey1->getId()] = $pixKey1;
        $this->pixKeys[$pixKey2->getId()] = $pixKey2;
        $this->pixKeys[$pixKey3->getId()] = $pixKey3;
        $this->pixKeys[$pixKey4->getId()] = $pixKey4;
    }

    public function find(string $id): ?PixKey
    {
        return $this->pixKeys[$id] ?? null;
    }
    
    public function findByValue(string $keyValue): ?PixKey
    {
        foreach ($this->pixKeys as $pixKey) {
            if ($pixKey->getKeyValue()->getValue() === $keyValue) {
                return $pixKey;
            }
        }
        
        return null;
    }
    
    public function findByAccountId(string $accountId): array
    {
        $result = [];
        
        foreach ($this->pixKeys as $pixKey) {
            if ($pixKey->getAccountId()->getValue() === $accountId) {
                $result[] = $pixKey;
            }
        }
        
        return $result;
    }
    
    public function save(PixKey $pixKey): PixKey
    {
        $this->pixKeys[$pixKey->getId()] = $pixKey;
        return $pixKey;
    }
    
    public function delete(string $id): bool
    {
        if (isset($this->pixKeys[$id])) {
            unset($this->pixKeys[$id]);
            return true;
        }
        
        return false;
    }
}
