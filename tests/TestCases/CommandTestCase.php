<?php

namespace SigmaPHP\DB\Tests\TestCases;

use SigmaPHP\Console\Command;
use SigmaPHP\DB\Tests\TestCases\DbTestCase;
use SigmaPHP\Console\IO;
use SigmaPHP\Console\Option;
use SigmaPHP\Console\DataType;

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

        if (!file_exists('fake_input_stream')) {
            touch('fake_input_stream');

            $this->ioHandler->setInputStream(fopen('fake_input_stream', 'r+'));
        }

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

        if (file_exists('fake_input_stream')) {
            unlink('fake_input_stream');
        }
    }

    /**
     * Command factory.
     *
     * @param string $class
     * @return Command
     */
    protected function commandFactory($class)
    {
        $command = new $class();

        $command->setIOHandler($this->ioHandler);

        $command->addOption(
            'config',
            '',
            'Set config file path',
            Option::PARAMETER_REQUIRED,
            DataType::STRING
        );

        $command->_options()['config']->setValue('config.php');

        return $command;
    }

    /**
     * Inject input into a stream.
     *
     * @param string $input
     * @return void
     */
    protected function injectInput($input)
    {
        $res = fopen('fake_input_stream', 'r+');

        ftruncate($res, 0);
        rewind($res);
        fwrite($res, $input);
        rewind($res);
    }
}
