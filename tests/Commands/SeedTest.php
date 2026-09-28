<?php

use SigmaPHP\DB\Tests\TestCases\CommandTestCase;
use SigmaPHP\DB\Commands\Seed;
use SigmaPHP\Console\DataType;
use SigmaPHP\Console\Option;

/**
 * Seed Command Test
 */
class SeedTest extends CommandTestCase
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

        $command = new _Seed();

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

        $query = $this->connectToDatabase()->prepare("
            SELECT * FROM users;
        ");

        $query->execute();

        $this->assertEquals([
            'id' => 1,
            'name' => "Ahmed",
            'email' => "ahmed@example.com",
            'age' => 15,
        ], $query->fetch(\PDO::FETCH_ASSOC));

        $this->dropTestTable('users');
    }

    /**
     * Test file option.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testFileOption()
    {
        $this->createTestTable('users');

        $command = new _Seed();

        $command->setIOHandler($this->ioHandler);
        $command->addOption(
            'config',
            '',
            'Set config file path',
            Option::PARAMETER_REQUIRED,
            DataType::STRING
        );

        $command->_options()['config']->setValue('config.php');
        $command->_options()['file']->setValue('UsersSeeder.php');

        $command->execute();

        $query = $this->connectToDatabase()->prepare("
            SELECT * FROM users;
        ");

        $query->execute();

        $this->assertEquals([
            'id' => 1,
            'name' => "Ahmed",
            'email' => "ahmed@example.com",
            'age' => 15,
        ], $query->fetch(\PDO::FETCH_ASSOC));

        $this->dropTestTable('users');
    }
}

class _Seed extends Seed
{
    public function _options() {return $this->options;}
    public function _arguments() {return $this->arguments;}
}
