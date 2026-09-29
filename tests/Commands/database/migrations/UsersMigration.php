<?php

use SigmaPHP\DB\Migrations\Migration;

class UsersMigration extends Migration
{
    /**
     * @return void
     */
    public function up()
    {
        $this->createTable(
            'users',
            [
                ['name' => 'id', 'type' => 'bigint', 'primary' => true],
                ['name' => 'name', 'type' => 'varchar'],
                ['name' => 'email', 'type' => 'varchar'],
                ['name' => 'age', 'type' => 'int'],
            ]
        );
    }

    /**
     * @return void
     */
    public function down()
    {
        $this->dropTable('users');
    }
}
