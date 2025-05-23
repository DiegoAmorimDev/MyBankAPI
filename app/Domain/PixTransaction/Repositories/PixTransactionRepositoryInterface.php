<?php

namespace App\Domain\PixTransaction\Repositories;

use App\Domain\PixTransaction\Entities\PixTransaction;

interface PixTransactionRepositoryInterface
{
    public function find(string $id): ?PixTransaction;
    public function findByAccountId(string $accountId): array;
    public function save(PixTransaction $pixTransaction): PixTransaction;
    public function update(PixTransaction $pixTransaction): PixTransaction;
    public function transferFunds(string $sourceAccountId, string $destinationAccountId, float $amount): bool;
}
