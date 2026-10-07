<?php

use SigmaPHP\DB\Tests\TestCases\CommandTestCase;
use SigmaPHP\DB\Commands\CreateMigration;
use SigmaPHP\Console\DataType;
use SigmaPHP\Console\Option;

/**
 * Create Migration Command Test
 */
class CreateMigrationTest extends CommandTestCase
{
    /**
     * Test command execution.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testCommandExecution()
    {
        $targetPath = __DIR__ . '/database/migrations/FooMigration.php';

        $command = $this->commandFactory(_CreateMigration::class);

        $this->injectInput('Yes');

        $command->_arguments()['name']->setValue('Foo');
        $command->_options()['config']->setValue('config.php');

        $command->execute();

        $this->assertTrue(file_exists($targetPath));

        if (file_exists($targetPath)) {
            unlink($targetPath);
        }
    }
}

class _CreateMigration extends CreateMigration
{
    public function _options() {return $this->options;}
    public function _arguments() {return $this->arguments;}
}
