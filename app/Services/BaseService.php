<?php

namespace App\Services;

use App\Repositories\Rolls\RollRepository;
use App\Repositories\Users\UserApiTokenRepository;
use App\Repositories\Users\UserRepository;
use PDO;

class BaseService
{
    protected PDO $pdo;

    protected UserRepository $users;
    protected UserApiTokenRepository $tokens;
    protected RollRepository $rolls;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;

        $this->users    = new UserRepository($pdo);
        $this->tokens   = new UserApiTokenRepository($pdo);
        $this->rolls    = new RollRepository($pdo);
    }

    public function getPdo(): PDO
    {
        return $this->pdo;
    }
}
