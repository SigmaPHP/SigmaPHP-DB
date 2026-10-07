<?php

use SigmaPHP\DB\Tests\TestCases\CommandTestCase;
use SigmaPHP\DB\Commands\CreateConfig;

/**
 * Create Config Command Test
 */
class CreateConfigTest extends CommandTestCase
{
    /**
     * Test command execution.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testCommandExecution()
    {
        $targetPath = 'database.php';

        $command = $this->commandFactory(CreateConfig::class);

        $command->execute();

        $this->assertTrue(file_exists($targetPath));

        if (file_exists($targetPath)) {
            unlink($targetPath);
        }
    }

    /**
     * Test path option.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testPathOption()
    {
        $command = $this->commandFactory(CreateConfig::class);

        $command->getOptions()['path']->setValue(__DIR__ . '/custom.php');

        $command->execute();

        $this->assertTrue(file_exists(__DIR__ . '/custom.php'));

        if (file_exists(__DIR__ . '/custom.php')) {
            unlink(__DIR__ . '/custom.php');
        }
    }
}
