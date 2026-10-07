<?php

use SigmaPHP\DB\Tests\TestCases\CommandTestCase;
use SigmaPHP\DB\Commands\Rollback;
use SigmaPHP\Console\DataType;
use SigmaPHP\Console\Option;
use SigmaPHP\DB\Migrations\Logger;

/**
 * Rollback Command Test
 */
class RollbackTest extends CommandTestCase
{
    /**
     * Test command execution.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testCommandExecution()
    {
        $this->createTestTable('users');

        // create fake log for users table creation
        $logger = new Logger($this->connectToDatabase(), 'db_logs');

        $logger->log('UsersMigration');

        $command = $this->commandFactory(Rollback::class);

        $command->execute();

        $this->assertFalse($this->checkTableExists('users'));

        $this->dropTestTable('db_logs');
    }

    /**
     * Test date option.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testDateOption()
    {
        $this->createTestTable('users');

        // create fake log for users table creation
        $logger = new Logger($this->connectToDatabase(), 'db_logs');

        $logger->log('UsersMigration');

        $command = $this->commandFactory(Rollback::class);

        $command->getOptions()['date']->setValue(date('Y-m-d'));

        $command->execute();

        $this->assertFalse($this->checkTableExists('users'));

        $this->dropTestTable('db_logs');
    }
}
