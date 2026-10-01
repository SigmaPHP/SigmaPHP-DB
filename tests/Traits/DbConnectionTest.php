<?php

use SigmaPHP\DB\Exceptions\InvalidConfigurationException;
use SigmaPHP\DB\Tests\TestCases\DbTestCase;
use SigmaPHP\DB\Traits\DbConnection;

/**
 * DbConnection Test
 */
class DbConnectionTest extends DbTestCase
{
    /**
     * @var object $testTrait
     */
    private $testTrait;

    /**
     * DbConnectionTest SetUp
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        // create new instance of anonymous class that
        // implements DbConnection Trait
        $this->testTrait = $this->createTestObject();
    }

    /**
     * Create new instance from test class.
     *
     * @return object
     */
    private function createTestObject()
    {
        return new class() {
            use DbConnection;
        };
    }

    /**
     * Test connection.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testConnection()
    {
        $this->assertInstanceOf(
            \PDO::class,
            $this->testTrait->getDbConnection($this->dbConfigs)
        );
    }

    /**
     * Test throws exception if invalid configs.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testThrowsExceptionIfInvalidConfigs()
    {
        $this->expectException(InvalidConfigurationException::class);

        $this->testTrait->getDbConnection([]);
    }
}
