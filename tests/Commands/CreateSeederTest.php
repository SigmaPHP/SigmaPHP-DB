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

        $command = $this->commandFactory(_CreateSeeder::class);

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
