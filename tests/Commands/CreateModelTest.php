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

    /**
     * Test model won't be created if already exists.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testModelWillNotBeCreatedIfAlreadyExists()
    {
        $targetPath = __DIR__ . '/database/models/Users.php';

        if (!file_exists($targetPath)) {
            touch($targetPath);
        }

        $this->ioHandler->setOutputStream(fopen('php://output', 'r+'));

        $command = $this->commandFactory(CreateModel::class);

        $command->getArguments()['name']->setValue('Users');

        $command->execute();

        $this->expectOutputRegex("/The model 'Users' already exists/");

        if (file_exists($targetPath)) {
            unlink($targetPath);
        }
    }

    /**
     * Test 'with-migration' option.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testWithMigrationOption()
    {
        $targetPathModel = __DIR__ . '/database/models/Users.php';
        $targetPathMigration = __DIR__ .
            '/database/migrations/CreateUsersTableMigration.php';

        $command = $this->commandFactory(CreateModel::class);

        $command->getArguments()['name']->setValue('Users');
        $command->getOptions()['with-migration']->setValue(true);

        $command->execute();

        $this->assertTrue(file_exists($targetPathModel));
        $this->assertTrue(file_exists($targetPathMigration));

        if (file_exists($targetPathModel)) {
            unlink($targetPathModel);
        }

        if (file_exists($targetPathMigration)) {
            unlink($targetPathMigration);
        }
    }
}
