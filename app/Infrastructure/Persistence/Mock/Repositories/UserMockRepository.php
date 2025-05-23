<?php
// app/Infrastructure/Persistence/Mock/Repositories/UserMockRepository.php
namespace Infrastructure\Persistence\Mock\Repositories;

use Domain\Auth\Repositories\UserRepositoryInterface;
use Domain\Auth\Entities\User;
use Illuminate\Support\Facades\Hash;

class UserMockRepository implements UserRepositoryInterface
{
    private array $users = [];
    private array $balances = [];

    public function __construct()
    {
        // Inicializa usuários mockados
        $user1 = new User();
        $user1->id = 1;
        $user1->name = 'João Silva';
        $user1->email = 'joao@example.com';
        $user1->cpf = '12345678900';
        $user1->password = Hash::make('senha123');
        
        $user2 = new User();
        $user2->id = 2;
        $user2->name = 'Maria Souza';
        $user2->email = 'maria@example.com';
        $user2->cpf = '98765432100';
        $user2->password = Hash::make('senha456');

        // Armazena usuários no array
        $this->users[1] = $user1;
        $this->users[2] = $user2;
        
        // Inicializa saldos mockados
        $this->balances[1] = 1250.75;
        $this->balances[2] = 3500.50;
    }

    public function find($id): ?User
    {
        return $this->users[$id] ?? null;
    }
    
    public function findByCpf(string $cpf): ?User
    {
        foreach ($this->users as $user) {
            if ($user->cpf === $cpf) {
                return $user;
            }
        }
        
        return null;
    }
    
    public function authenticate(string $cpf, string $password): ?User
    {
        $user = $this->findByCpf($cpf);
        
        if ($user && Hash::check($password, $user->password)) {
            return $user;
        }
        
        return null;
    }
    
    public function getBalance(int $userId): float
    {
        return $this->balances[$userId] ?? 0.0;
    }
}
