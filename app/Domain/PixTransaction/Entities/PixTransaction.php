<?php

namespace App\Domain\PixTransaction\Entities;

use App\Domain\PixKey\ObjectValues\AccountId; // Assuming sender/receiver are identified by AccountId
use App\Domain\PixTransaction\ObjectValues\Amount;
use App\Domain\PixTransaction\ObjectValues\Description;
use App\Domain\PixTransaction\ObjectValues\TransactionId;
use App\Domain\PixTransaction\ObjectValues\Timestamp;

class PixTransaction
{
    private TransactionId $id;
    private AccountId $payerAccountId;
    private AccountId $payeeAccountId;
    private Amount $amount;
    private ?Description $description;
    private Timestamp $createdAt;
    private Timestamp $updatedAt;
    private string $status; // e.g., PENDING, COMPLETED, FAILED

    public function __construct(
        AccountId $payerAccountId,
        AccountId $payeeAccountId,
        Amount $amount,
        ?Description $description = null
    ) {
        $this->id = new TransactionId(); // Auto-generate ID
        $this->payerAccountId = $payerAccountId;
        $this->payeeAccountId = $payeeAccountId;
        $this->amount = $amount;
        $this->description = $description;
        $this->createdAt = new Timestamp();
        $this->updatedAt = new Timestamp();
        $this->status = 'PENDING'; // Initial status
    }

    public function getId(): TransactionId
    {
        return $this->id;
    }

    public function getPayerAccountId(): AccountId
    {
        return $this->payerAccountId;
    }

    public function getPayeeAccountId(): AccountId
    {
        return $this->payeeAccountId;
    }

    public function getAmount(): Amount
    {
        return $this->amount;
    }

    public function getDescription(): ?Description
    {
        return $this->description;
    }

    public function getCreatedAt(): Timestamp
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): Timestamp
    {
        return $this->updatedAt;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function complete(): void
    {
        $this->status = 'COMPLETED';
        $this->updatedAt = new Timestamp();
    }

    public function fail(): void
    {
        $this->status = 'FAILED';
        $this->updatedAt = new Timestamp();
    }
}

