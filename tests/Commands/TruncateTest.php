<?php

use SigmaPHP\DB\Tests\TestCases\CommandTestCase;
use SigmaPHP\DB\Commands\Truncate;
use SigmaPHP\Console\DataType;
use SigmaPHP\Console\Option;

/**
 * Truncate Command Test
 */
class TruncateTest extends CommandTestCase
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

        $command = new _Truncate();

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

        $dataWasTruncated = $this->connectToDatabase()->prepare("
            SELECT * FROM users;
        ");

        $dataWasTruncated->execute();
        $this->assertEquals(0, count($dataWasTruncated->fetchAll()));

        $this->dropTestTable('users');
    }
}

class _Truncate extends Truncate
{
    public function _options() {return $this->options;}
    public function _arguments() {return $this->arguments;}
}
