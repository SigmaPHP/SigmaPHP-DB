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

        $command = $this->commandFactory(CreateSeeder::class);

        $command->getArguments()['name']->setValue('Foo');

        $command->execute();

        $this->assertTrue(file_exists($targetPath));

        if (file_exists($targetPath)) {
            unlink($targetPath);
        }
    }

    /**
     * Test seeder won't be created if already exists.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testSeederWillNotBeCreatedIfAlreadyExists()
    {
        $this->ioHandler->setOutputStream(fopen('php://output', 'r+'));

        $command = $this->commandFactory(CreateSeeder::class);

        $command->getArguments()['name']->setValue('Users');

        $command->execute();

        $this->expectOutputRegex(
            "/The seeder 'UsersSeeder' already exists/"
        );
    }
}
