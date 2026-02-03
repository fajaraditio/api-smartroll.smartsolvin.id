<?php

namespace App\Repositories\Rolls;

use App\Repositories\BaseRepository;

class RollRepository extends BaseRepository
{
    public function getAll($perPage, $offset): array
    {
        return $this->fetchAll(
            "SELECT
                        id,
                        name,
                        width,
                        length,
                        thickness,
                        color,
                        price_per_meter,
                        DATE(created_at) as created_at,
                        DATE(updated_at) as updated_at,
                        JSON_ARRAY(
                            CASE WHEN created_at >= NOW() - INTERVAL 7 DAY THEN 'New' ELSE NULL END,
                            'Unused'
                        ) AS statuses
                    FROM rolls 
                    ORDER BY created_at DESC
                    LIMIT :perPage OFFSET :offset;",
            [
                'perPage' => $perPage,
                'offset' => $offset
            ]
        );
    }

    public function count(): int
    {
        $result = $this->fetchOne("SELECT COUNT(*) as count FROM rolls");

        return $result ? (int) $result['count'] ?? 0 : 0;
    }

    public function findById(int $id): ?array
    {
        return $this->fetchOne(
            "SELECT
                id,
                name,
                width,
                length,
                thickness,
                color,
                price_per_meter,
                created_at,
                updated_at,
                JSON_ARRAY(
                    CASE WHEN created_at >= NOW() - INTERVAL 7 DAY THEN 'new' ELSE NULL END,
                    'unused'
                ) AS statuses
            FROM rolls
            WHERE id = :id",
            ['id' => $id]
        ) ?: null;
    }

    public function create(array $data): int
    {
        return $this->execute(
            "INSERT INTO rolls 
                (name, width, length, thickness, color, price_per_meter, created_at, updated_at)
                VALUES (:name, :width, :length, :thickness, :color, :price_per_meter, NOW(), NOW())",
            $data
        );
    }

    public function update(int $id, array $data): int
    {
        return $this->execute(
            "UPDATE rolls SET 
                name = :name,
                width = :width,
                length = :length,
                thickness = :thickness,
                color = :color,
                price_per_meter = :price_per_meter,
                updated_at = NOW()
            WHERE id = :id",
            $data
        );
    }

    public function delete(int $id): int
    {
        return $this->execute(
            "DELETE FROM rolls WHERE id = :id",
            ['id' => $id]
        );
    }
}
