<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddUserIdIndexToUserApiTokens extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('user_api_tokens');
        $table->addIndex('user_id')
            ->update();
    }
}
