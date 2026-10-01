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
        $command = new _CreateConfig();

        $command->setIOHandler($this->ioHandler);
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
        $command = new _CreateConfig();

        $command->setIOHandler($this->ioHandler);
        $command->_options()['path']->setValue(__DIR__ . '/custom.php');

        $command->execute();

        $this->assertTrue(file_exists(__DIR__ . '/custom.php'));

        if (file_exists(__DIR__ . '/custom.php')) {
            unlink(__DIR__ . '/custom.php');
        }
    }
}

class _CreateConfig extends CreateConfig
{
    public function _options() {return $this->options;}
    public function _arguments() {return $this->arguments;}
}
