<?php


use Phinx\Seed\AbstractSeed;

class UserAdministratorSeeder extends AbstractSeed
{
    public function run()
    {
        $password = password_hash('123123123', PASSWORD_BCRYPT, ['cost' => 10]);

        $data = [
            [
                'user_id'   => 1,
                'name'      => 'Administrator',
                'email'     => 'admin@smartsolvin.id',
                'password'  => $password,
                'role'      => 'Administrator',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]
        ];

        $this->table('users')->insert($data)->saveData();
    }
}
