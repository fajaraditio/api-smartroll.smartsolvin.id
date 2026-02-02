<?php

namespace App\Repositories\Users;

use App\Repositories\BaseRepository;

class UserApiTokenRepository extends BaseRepository
{
    public function createToken(int $userId, string $token, ?string $expiresAt = null): int
    {
        return $this->execute(
            "INSERT INTO user_api_tokens (user_id, token, expires_at) VALUES (:user_id, :token, :expires_at)",
            [
                'user_id'       => $userId,
                'token'         => $token,
                'expires_at'    => $expiresAt
            ]
        );
    }

    public function findUserByToken(string $token): ?array
    {
        return $this->fetchOne(
            "SELECT t.*, u.name, u.email, u.role FROM user_api_tokens t 
             JOIN users u ON t.user_id = u.user_id 
             WHERE t.token = :token AND (t.expires_at IS NULL OR t.expires_at > NOW())",
            ['token' => $token]
        );
    }
}
