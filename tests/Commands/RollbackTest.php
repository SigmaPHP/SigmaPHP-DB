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

        $command = new _Rollback();

        $command->setIOHandler($this->ioHandler);
        $command->addOption(
            'config',
            '',
            'Set config file path',
            Option::PARAMETER_REQUIRED,
            DataType::STRING
        );

        $command->_options()['config']->setValue('config.php');

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

        $command = new _Rollback();

        $command->setIOHandler($this->ioHandler);
        $command->addOption(
            'config',
            '',
            'Set config file path',
            Option::PARAMETER_REQUIRED,
            DataType::STRING
        );

        $command->_options()['config']->setValue('config.php');
        $command->_options()['date']->setValue(date('Y-m-d'));

        $command->execute();

        $this->assertFalse($this->checkTableExists('users'));

        $this->dropTestTable('db_logs');
    }
}

class _Rollback extends Rollback
{
    public function _options() {return $this->options;}
    public function _arguments() {return $this->arguments;}
}
