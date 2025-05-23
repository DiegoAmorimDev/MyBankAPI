<?php

namespace App\Domain\PixKey\Repositories;

use App\Domain\PixKey\Entities\PixKey;

interface PixKeyRepositoryInterface
{
    public function find(string $id): ?PixKey;
    public function findByValue(string $keyValue): ?PixKey;
    public function findByAccountId(string $accountId): array;
    public function save(PixKey $pixKey): PixKey;
    public function delete(string $id): bool;
}
