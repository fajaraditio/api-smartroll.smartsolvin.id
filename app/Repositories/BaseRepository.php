<?php

namespace App\Repositories;

use PDO;

class BaseRepository
{
    protected PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    protected function fetchAll(string $sql, array $params = []): array
    {
        $stmt = $this->pdo->prepare($sql);

        foreach ($params as $key => $value) {
            if (is_int($value)) {
                $stmt->bindValue(is_string($key) ? $key : $key + 1, $value, PDO::PARAM_INT);
            } elseif (is_bool($value)) {
                $stmt->bindValue(is_string($key) ? $key : $key + 1, $value, PDO::PARAM_BOOL);
            } elseif (is_null($value)) {
                $stmt->bindValue(is_string($key) ? $key : $key + 1, $value, PDO::PARAM_NULL);
            } else {
                $stmt->bindValue(is_string($key) ? $key : $key + 1, $value, PDO::PARAM_STR);
            }
        }

        $stmt->execute();

        return $stmt->fetchAll();
    }

    protected function fetchOne(string $sql, array $params = []): ?array
    {
        $stmt = $this->pdo->prepare($sql);

        foreach ($params as $key => $value) {
            if (is_int($value)) {
                $stmt->bindValue(is_string($key) ? $key : $key + 1, $value, PDO::PARAM_INT);
            } elseif (is_bool($value)) {
                $stmt->bindValue(is_string($key) ? $key : $key + 1, $value, PDO::PARAM_BOOL);
            } elseif (is_null($value)) {
                $stmt->bindValue(is_string($key) ? $key : $key + 1, $value, PDO::PARAM_NULL);
            } else {
                $stmt->bindValue(is_string($key) ? $key : $key + 1, $value, PDO::PARAM_STR);
            }
        }

        $stmt->execute();
        $result = $stmt->fetch();

        return $result === false ? null : $result;
    }

    protected function execute(string $sql, array $params = []): int
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $this->pdo->lastInsertId();
    }
}
