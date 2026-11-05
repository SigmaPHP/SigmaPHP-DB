<?php

use PHPUnit\Framework\TestCase;
use SigmaPHP\DB\Connectors\Connector;

/**
 * Connector Test
 */
class ConnectorTest extends TestCase
{
    /**
     * @var array $dbConfigs
     */
    private $dbConfigs;

    /**
     * @var Connector $connector
     */
    private $connector;

    /**
     * ConnectorTest SetUp
     *
     * @return void
     */
    public function setUp(): void
    {
        // add your database configs to phpunit.xml
        $this->dbConfigs = [
            'host' => $GLOBALS['DB_HOST'],
            'name' => $GLOBALS['DB_NAME'],
            'user' => $GLOBALS['DB_USER'],
            'pass' => $GLOBALS['DB_PASS'],
            'port' => $GLOBALS['DB_PORT']
        ];

        // create new connector
        $this->connector = new Connector($this->dbConfigs);
    }

    /**
     * Test PDO connection is created successfully.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testPdoConnectionIsCreatedSuccessfully()
    {
        $this->assertInstanceOf(
            PDO::class,
            $this->connector->connect()
        );
    }

    /**
     * Test PDO connection could have custom options.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testPdoConnectionCouldHaveCustomOptions()
    {
        $this->connector = new Connector(
            $this->dbConfigs,
            [
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]
        );

        $this->assertEquals(
            PDO::FETCH_ASSOC,
            $this->connector->connect()
                ->getAttribute(PDO::ATTR_DEFAULT_FETCH_MODE)
        );
    }
}
