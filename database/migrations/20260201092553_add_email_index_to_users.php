<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddEmailIndexToUsers extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('users');
        $table->addIndex('email', ['unique' => true])
            ->update();
    }
}
