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

        $command = $this->commandFactory(CreateMigration::class);

        $this->injectInput('Yes');

        $command->getArguments()['name']->setValue('Foo');

        $command->execute();

        $this->assertTrue(file_exists($targetPath));

        if (file_exists($targetPath)) {
            unlink($targetPath);
        }
    }
}
