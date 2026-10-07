<?php

use SigmaPHP\DB\Tests\TestCases\CommandTestCase;
use SigmaPHP\DB\Commands\Seed;
use SigmaPHP\Console\DataType;
use SigmaPHP\Console\Option;

/**
 * Seed Command Test
 */
class SeedTest extends CommandTestCase
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

        $command = $this->commandFactory(Seed::class);

        $command->execute();

        $query = $this->connectToDatabase()->prepare("
            SELECT * FROM users;
        ");

        $query->execute();

        $this->assertEquals([
            'id' => 1,
            'name' => "Ahmed",
            'email' => "ahmed@example.com",
            'age' => 15,
        ], $query->fetch(\PDO::FETCH_ASSOC));

        $this->dropTestTable('users');
    }

    /**
     * Test file option.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testFileOption()
    {
        $this->createTestTable('users');

        $command = $this->commandFactory(Seed::class);

        $command->execute();

        $query = $this->connectToDatabase()->prepare("
            SELECT * FROM users;
        ");

        $query->execute();

        $this->assertEquals([
            'id' => 1,
            'name' => "Ahmed",
            'email' => "ahmed@example.com",
            'age' => 15,
        ], $query->fetch(\PDO::FETCH_ASSOC));

        $this->dropTestTable('users');
    }
}
