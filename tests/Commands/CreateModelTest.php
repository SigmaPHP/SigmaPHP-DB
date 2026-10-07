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

        $command = $this->commandFactory(CreateModel::class);

        $command->getArguments()['name']->setValue('Users');

        $command->execute();

        $this->assertTrue(file_exists($targetPath));

        if (file_exists($targetPath)) {
            unlink($targetPath);
        }
    }
}
