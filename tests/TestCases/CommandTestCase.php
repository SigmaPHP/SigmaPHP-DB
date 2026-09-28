<?php

namespace SigmaPHP\DB\Tests\TestCases;

use SigmaPHP\DB\Tests\TestCases\DbTestCase;
use SigmaPHP\Console\IO;

/**
 * Command Test Case
 */
class CommandTestCase extends DbTestCase
{
    /**
     * @var IO $ioHandler
     */
    protected $ioHandler;

    /**
     * CommandTestCase SetUp
     *
     * @return void
     */
    public function setUp(): void
    {
        $this->dbConfigs = [
            'host' => $GLOBALS['DB_HOST'],
            'name' => $GLOBALS['DB_NAME'],
            'user' => $GLOBALS['DB_USER'],
            'pass' => $GLOBALS['DB_PASS'],
            'port' => $GLOBALS['DB_PORT']
        ];

        $this->ioHandler = new IO();
        $this->ioHandler->setOutputStream(fopen('php://memory', 'w+'));

        if (!file_exists('config.php')) {
            $path = 'tests/Commands/database';

            file_put_contents(
                'config.php',
                <<<CONFIG
                <?php

                return [
                    'path_to_migrations'  => '{$path}/migrations',
                    'path_to_seeders'     => '{$path}/seeders',
                    'path_to_models'      => '{$path}/models',
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
    }

    /**
     * CommandTestCase TearDown
     *
     * @return void
     */
    public function tearDown(): void
    {
        if (file_exists('config.php')) {
            unlink('config.php');
        }
    }
}
