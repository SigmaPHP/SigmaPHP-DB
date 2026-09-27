<?php

namespace SigmaPHP\DB\Tests\TestCases;

use PHPUnit\Framework\TestCase;
use SigmaPHP\Console\IO;

/**
 * Command Test Case
 */
class CommandTestCase extends TestCase
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
        $this->ioHandler = new IO();
        $this->ioHandler->setOutputStream(fopen('php://memory', 'w+'));

        if (!file_exists('config.php')) {
            file_put_contents(
                'config.php',
                <<<CONFIG
                <?php

                return [
                    'path_to_migrations'  => '',
                    'path_to_seeders'     => '',
                    'path_to_models'      => '',
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
