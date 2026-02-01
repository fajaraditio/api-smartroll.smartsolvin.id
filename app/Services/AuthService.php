<?php

namespace App\Services;

use App\Repositories\Users\UserApiTokenRepository;
use App\Repositories\Users\UserRepository;

class AuthService
{
    protected UserRepository $users;
    protected UserApiTokenRepository $tokens;

    public function __construct(UserRepository $users, UserApiTokenRepository $tokens)
    {
        $this->users = $users;
        $this->tokens = $tokens;
    }

    public function login(string $email, string $password): array
    {
        $user = $this->users->findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            throw new \Exception('Invalid credentials');
        }

        $rawToken = bin2hex(random_bytes(12));
        $hashedToken = hash('sha256', random_bytes(10));
        $expiresAt = date('Y-m-d H:i:s', strtotime('+30 days'));

        $this->tokens->createToken((int)$user['user_id'], $hashedToken, $expiresAt);

        return [
            'user' => $user,
            'token' => $rawToken,
            'expires_at' => $expiresAt
        ];
    }
}
