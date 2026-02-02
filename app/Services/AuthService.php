<?php

namespace App\Services;

use App\Repositories\Users\UserApiTokenRepository;
use App\Repositories\Users\UserRepository;

class AuthService
{
    protected UserRepository $users;
    protected UserApiTokenRepository $tokens;
    protected int $tokenExpirationDays = 30;

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
        $hashedToken = hash('sha256', $rawToken);
        $expiresAt = date('Y-m-d H:i:s', strtotime('+' . $this->tokenExpirationDays . ' days'));

        $this->tokens->createToken((int)$user['user_id'], $hashedToken, $expiresAt);

        $this->setCookie($rawToken);

        return [
            'user' => $user,
            'token' => $rawToken,
            'expires_at' => $expiresAt
        ];
    }

    public function getUserByToken(string $token): ?array
    {
        $hashedToken = hash('sha256', $token);
        $user = $this->tokens->findUserByToken($hashedToken);

        if (!$user) return null;

        return $user;
    }

    public function setCookie($token): void
    {
        setcookie(
            'access_token',
            $token,
            [
                'expires' => time() + 60 * 60 * 24 * $this->tokenExpirationDays,
                'path' => '/',
                'domain' => env('APP_DOMAIN', 'smartroll.smartsolvin.id'),
                'secure' => env('APP_ENV', 'production') === 'production',
                'httponly' => true,
                'samesite' => 'Lax'
            ]
        );
    }
}
