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
                ['name' => 'age', 'type' => 'varchar'],
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
