<?php

use SigmaPHP\DB\Seeders\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * @return void
     */
    public function run()
    {
        $this->insert(
            'users',
            [
                ['name' => 'Ahmed', 'email' => 'ahmed@example.com', 'age' => 15]
            ]
        );
    }
}
