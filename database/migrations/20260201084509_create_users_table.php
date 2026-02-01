<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateUsersTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('users', ['id' => false, 'primary_key' => 'user_id']);

        $table->addColumn('user_id', 'biginteger', ['signed' => false, 'identity' => true])
            ->addColumn('name', 'string', ['limit' => 255])
            ->addColumn('email', 'string', ['limit' => 255])
            ->addColumn('password', 'string', ['limit' => 255])
            ->addColumn('role', 'enum', [
                'values' => ['Administrator', 'Staff', 'Designer'],
                'default' => 'Administrator'
            ])
            ->addColumn('is_active', 'boolean', ['default' => true])
            ->addTimestamps()
            ->create();
    }
}
