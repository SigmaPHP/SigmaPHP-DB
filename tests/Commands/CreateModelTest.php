<?php

use SigmaPHP\DB\Tests\TestCases\CommandTestCase;
use SigmaPHP\DB\Commands\CreateModel;
use SigmaPHP\Console\DataType;
use SigmaPHP\Console\Option;

/**
 * Create Model Command Test
 */
class CreateModelTest extends CommandTestCase
{
    /**
     * Test command execution.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testCommandExecution()
    {
        $targetPath = __DIR__ . '/database/models/Users.php';

        $command = $this->commandFactory(_CreateModel::class);

        $command->_arguments()['name']->setValue('Users');
        $command->_options()['config']->setValue('config.php');

        $command->execute();

        $this->assertTrue(file_exists($targetPath));

        if (file_exists($targetPath)) {
            unlink($targetPath);
        }
    }
}

class _CreateModel extends CreateModel
{
    public function _options() {return $this->options;}
    public function _arguments() {return $this->arguments;}
}
