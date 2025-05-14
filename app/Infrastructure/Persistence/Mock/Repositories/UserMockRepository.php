<?php
// app/Infrastructure/Persistence/Mock/Repositories/UserMockRepository.php
namespace Infrastructure\Persistence\Mock\Repositories;

use Domain\Auth\Repositories\UserRepositoryInterface;
use Domain\Auth\Entities\User;

class UserMockRepository implements UserRepositoryInterface
{
    private array $users = [];

    public function find($id): ?User
    {
        return $this->users[$id] ?? null;
    }
    
    // Implemente outros métodos
}
?>