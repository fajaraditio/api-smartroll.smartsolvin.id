<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateUserApiTokensTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('user_api_tokens', ['id' => false, 'primary_key' => 'id']);

        $table->addColumn('id', 'biginteger', ['signed' => false, 'identity' => true])
            ->addColumn('user_id', 'biginteger', ['signed' => false])
            ->addColumn('token', 'string', ['limit' => 64])
            ->addColumn('expires_at', 'datetime', ['null' => true])
            ->addTimestamps()
            ->addForeignKey('user_id', 'users', 'user_id', [
                'delete' => 'CASCADE',
                'update' => 'NO_ACTION'
            ])
            ->create();
    }
}
