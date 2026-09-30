<?php

use SigmaPHP\DB\Tests\TestCases\CommandTestCase;
use SigmaPHP\DB\Commands\Fresh;
use SigmaPHP\Console\DataType;
use SigmaPHP\Console\Option;

/**
 * Fresh Command Test
 */
class FreshTest extends CommandTestCase
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

        $insert = $this->connectToDatabase()->prepare(<<<TEXT
            INSERT INTO users
                (name, email, age)
            VALUES
                ('ahmed', 'ahmed@example.com', 15);
        TEXT);

        $insert->execute();

        $command = new _Fresh();

        $command->setIOHandler($this->ioHandler);
        $command->addOption(
            'config',
            '',
            'Set config file path',
            Option::PARAMETER_REQUIRED,
            DataType::STRING
        );

        $command->_options()['config']->setValue('config.php');

        $res = fopen('fake_input_stream', 'r+');
        ftruncate($res, 0);
        rewind($res);
        fwrite($res, 'YES');
        rewind($res);

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
        $this->dropTestTable('db_logs');
    }
}

class _Fresh extends Fresh
{
    public function _options() {return $this->options;}
    public function _arguments() {return $this->arguments;}
}
