<?php

namespace Domain\Auth\Repositories;

use Domain\Auth\Entities\User;

interface UserRepositoryInterface
{
    public function find($id): ?User;
    public function findByCpf(string $cpf): ?User;
    public function authenticate(string $cpf, string $password): ?User;
    public function getBalance(int $userId): float;
}
