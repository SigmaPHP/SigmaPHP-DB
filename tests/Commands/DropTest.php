<?php

use SigmaPHP\DB\Tests\TestCases\CommandTestCase;
use SigmaPHP\DB\Commands\Drop;
use SigmaPHP\Console\DataType;
use SigmaPHP\Console\Option;

/**
 * Drop Command Test
 */
class DropTest extends CommandTestCase
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

        $command = new _Drop();

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

        $this->assertFalse($this->checkTableExists('users'));
    }
}

class _Drop extends Drop
{
    public function _options() {return $this->options;}
    public function _arguments() {return $this->arguments;}
}
