<?php

use SigmaPHP\DB\Exceptions\InvalidConfigurationException;
use SigmaPHP\DB\Tests\TestCases\DbTestCase;
use SigmaPHP\DB\Traits\DbConfigs;

/**
 * DbConfigs Test
 */
class DbConfigsTest extends DbTestCase
{
    /**
     * @var object $testTrait
     */
    private $testTrait;

    /**
     * DbConfigsTest SetUp
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        // create new instance of anonymous class that
        // implements DbConfigs Trait
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
            use DbConfigs;
        };
    }

    /**
     * Test load configs from default path.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testLoadConfigsFromDefaultPath()
    {
        $this->assertEquals([
            'path_to_migrations'  => '/database/migrations',
            'path_to_seeders'     => '/database/seeders',
            'path_to_models'      => '/src/Models',
            'logs_table_name'     => 'db_logs',
            'database_connection' => [
                'host' => $GLOBALS['DB_HOST'],
                'name' => $GLOBALS['DB_NAME'],
                'user' => $GLOBALS['DB_USER'],
                'pass' => $GLOBALS['DB_PASS'],
                'port' => $GLOBALS['DB_PORT'],
            ]
        ], $this->testTrait->loadConfigs());
    }

    /**
     * Test load configs from custom path.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testLoadConfigsFromCustomPath()
    {
        // create the dummy config file
        $configFilePath = __DIR__ . '/custom.php';

        if (!file_exists($configFilePath)) {
            file_put_contents(
                $configFilePath,
                <<<CONFIG
                <?php

                return [
                    'path_to_migrations'  => './database/migrations',
                    'path_to_seeders'     => './database/seeders',
                    'path_to_models'      => './src/Models',
                    'logs_table_name'     => 'db_logs',
                    'database_connection' => [
                        'host' => '{$GLOBALS['DB_HOST']}',
                        'name' => '{$GLOBALS['DB_NAME']}',
                        'user' => '{$GLOBALS['DB_USER']}',
                        'pass' => '{$GLOBALS['DB_PASS']}',
                        'port' => '{$GLOBALS['DB_PORT']}',
                    ]
                ];
                CONFIG
            );
        }

        // assert
        $this->assertEquals([
            'path_to_migrations'  => 'database/migrations',
            'path_to_seeders'     => 'database/seeders',
            'path_to_models'      => 'src/Models',
            'logs_table_name'     => 'db_logs',
            'database_connection' => [
                'host' => $GLOBALS['DB_HOST'],
                'name' => $GLOBALS['DB_NAME'],
                'user' => $GLOBALS['DB_USER'],
                'pass' => $GLOBALS['DB_PASS'],
                'port' => $GLOBALS['DB_PORT'],
            ]
        ], $this->testTrait->loadConfigs(__DIR__ . '/custom.php'));

        // remove dummy config file
        if (file_exists($configFilePath)) {
            unlink($configFilePath);
        }
    }

    /**
     * Test throws exception if the path to config file does not exists.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testThrowsExceptionIfThePathToConfigFileDoesNotExists()
    {
        $this->expectException(InvalidConfigurationException::class);

        $this->testTrait->loadConfigs('unknown/path/');
    }
}
