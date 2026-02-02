<?php

namespace App\Repositories\Rolls;

use App\Repositories\BaseRepository;

class RollRepository extends BaseRepository
{
    public function getAll(): array
    {
        return $this->fetchAll("SELECT * FROM rolls");
    }

    public function count(): int
    {
        return $this->fetchOne("SELECT COUNT(*) as count FROM rolls")['count'] ?? 0;
    }

    public function findById(int $id): ?array
    {
        return $this->fetchOne("SELECT * FROM rolls WHERE id = :id", ['id' => $id]);
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
