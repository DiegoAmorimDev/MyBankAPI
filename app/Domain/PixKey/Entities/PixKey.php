<?php

namespace App\Domain\PixKey\Entities;

use App\Domain\PixKey\ObjectValues\KeyType;
use App\Domain\PixKey\ObjectValues\KeyValue;
use App\Domain\PixKey\ObjectValues\AccountId;

class PixKey
{
    private string $id;
    private AccountId $accountId;
    private KeyType $keyType;
    private KeyValue $keyValue;
    private \DateTimeImmutable $createdAt;

    public function __construct(
        AccountId $accountId,
        KeyType $keyType,
        KeyValue $keyValue
    ) {
        $this->id = uniqid();
        $this->accountId = $accountId;
        $this->keyType = $keyType;
        $this->keyValue = $keyValue;
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getAccountId(): AccountId
    {
        return $this->accountId;
    }

    public function getKeyType(): KeyType
    {
        return $this->keyType;
    }

    public function getKeyValue(): KeyValue
    {
        return $this->keyValue;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
}

