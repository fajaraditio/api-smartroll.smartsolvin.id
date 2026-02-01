<?php

namespace App\Repositories\Users;

use App\Repositories\BaseRepository;

class UserRepository extends BaseRepository
{
    public function findByEmail(string $email): ?array
    {
        return $this->fetchOne("SELECT * FROM users WHERE email = :email", ['email' => $email]);
    }

    public function create(array $data): int
    {
        return $this->execute(
            "INSERT INTO users (name, email, password, role, is_active) VALUES (:name, :email, :password, :role, :is_active)",
            $data
        );
    }
}
