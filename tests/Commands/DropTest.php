<?php

use SigmaPHP\DB\Tests\TestCases\CommandTestCase;
use SigmaPHP\DB\Commands\Drop;
use SigmaPHP\Console\DataType;
use SigmaPHP\Console\Option;

/**
 * Drop Command Test
 */
class DropTest extends CommandTestCase
{
    /**
     * Test command execution.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testCommandExecution()
    {
        $this->createTestTable('users');

        $command = $this->commandFactory(Drop::class);

        $this->injectInput('Yes');

        $command->execute();

        $this->assertFalse($this->checkTableExists('users'));
    }
}
