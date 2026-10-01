<?php

use SigmaPHP\DB\Tests\TestCases\CommandTestCase;
use SigmaPHP\DB\Commands\CreateSeeder;
use SigmaPHP\Console\DataType;
use SigmaPHP\Console\Option;

/**
 * Create Seeder Command Test
 */
class CreateSeederTest extends CommandTestCase
{
    /**
     * Test command execution.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testCommandExecution()
    {
        $targetPath = __DIR__ . '/database/seeders/FooSeeder.php';
        $command = new _CreateSeeder();

        $command->setIOHandler($this->ioHandler);
        $command->addOption(
            'config',
            '',
            'Set config file path',
            Option::PARAMETER_REQUIRED,
            DataType::STRING
        );

        $command->_arguments()['name']->setValue('Foo');
        $command->_options()['config']->setValue('config.php');

        $command->execute();

        $this->assertTrue(file_exists($targetPath));

        if (file_exists($targetPath)) {
            unlink($targetPath);
        }
    }
}

class _CreateSeeder extends CreateSeeder
{
    public function _options() {return $this->options;}
    public function _arguments() {return $this->arguments;}
}
