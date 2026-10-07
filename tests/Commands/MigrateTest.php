<?php

use SigmaPHP\DB\Tests\TestCases\CommandTestCase;
use SigmaPHP\DB\Commands\Migrate;
use SigmaPHP\Console\DataType;
use SigmaPHP\Console\Option;

/**
 * Migrate Command Test
 */
class MigrateTest extends CommandTestCase
{
    /**
     * Test command execution.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testCommandExecution()
    {
        $command = $this->commandFactory(Migrate::class);

        $command->execute();

        $this->assertTrue($this->checkTableExists('users'));
        $this->assertEquals(4, count($this->getTableFields('users')));

        $this->dropTestTable('users');
        $this->dropTestTable('db_logs');
    }

    /**
     * Test file option.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testFileOption()
    {
        $command = $this->commandFactory(Migrate::class);

        $command->execute();

        $this->assertTrue($this->checkTableExists('users'));
        $this->assertEquals(4, count($this->getTableFields('users')));

        $this->dropTestTable('users');
        $this->dropTestTable('db_logs');
    }
}
