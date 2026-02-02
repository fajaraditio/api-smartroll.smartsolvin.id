<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateRolls extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('rolls', ['id' => 'id']);

        $table->addColumn('name', 'string', ['limit' => 255])
            ->addColumn('width', 'decimal', ['precision' => 8, 'scale' => 2])
            ->addColumn('length', 'decimal', ['precision' => 8, 'scale' => 2])
            ->addColumn('thickness', 'decimal', ['precision' => 5, 'scale' => 3])
            ->addColumn('color', 'string', ['limit' => 50, 'null' => true])
            ->addColumn('price_per_meter', 'decimal', ['precision' => 12, 'scale' => 2])
            ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP'])
            ->addIndex(['name'], ['unique' => true])
            ->addIndex(['color'])
            ->addIndex(['price_per_meter'])
            ->create();
    }
}
