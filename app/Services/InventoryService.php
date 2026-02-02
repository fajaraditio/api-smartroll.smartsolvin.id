<?php

namespace App\Services;

use App\Repositories\Rolls\RollRepository;
use Exception;

class InventoryService
{
    protected RollRepository $rolls;

    public function __construct(RollRepository $rolls)
    {
        $this->rolls = $rolls;
    }

    public function createRoll(array $data)
    {
        $data['color'] = $data['color'] ?? null;

        return $this->rolls->create($data);
    }

    public function updateRoll(int $id, array $data)
    {
        $data['color'] = $data['color'] ?? null;

        $updated = $this->rolls->update($id, $data);
        if (!$updated) {
            throw new Exception("Roll with ID $id not found or nothing to update.");
        }

        return $id;
    }

    public function deleteRoll(int $id)
    {
        $deleted = $this->rolls->delete($id);
        if (!$deleted) {
            throw new Exception("Roll with ID $id not found or already deleted.");
        }

        return $id;
    }

    public function getRollById(int $id)
    {
        $roll = $this->rolls->findById($id);
        if (!$roll) {
            throw new Exception("Roll with ID $id not found.");
        }

        return $roll;
    }

    public function listRolls(int $page = 1, int $perPage = 10)
    {
        $offset = ($page - 1) * $perPage;

        $data = $this->rolls->getAll($perPage, $offset);
        $total = $this->rolls->count();

        return [
            'data'  => $data,
            'total' => $total
        ];
    }
}
