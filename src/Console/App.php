<?php

namespace SigmaPHP\DB\Console;

use SigmaPHP\Console\App as ConsoleApp;
use SigmaPHP\Console\DataType;
use SigmaPHP\Console\Option;
use SigmaPHP\Filesystem\Filesystem;

/**
 * Console App Class
 */
class App
{
    // define app version
    const VERSION = "v0.1.19";

    /**
     * Start console app.
     *
     * @return void
     */
    public static function start()
    {
        // initiate new console app
        $app = new ConsoleApp();

        $app->setAppName('sigma-db');
        $app->setAppDescription('CLI utilities for `SigmaPHP-DB`');
        $app->setAppVersion('SigmaPHP-DB ' . self::VERSION);

        $app->addGlobalOption(
            'config',
            '',
            'Set config file path',
            Option::PARAMETER_REQUIRED,
            DataType::STRING
        );

        $app->loadCommands(
            (new Filesystem())->concatPath(dirname(__DIR__, 1), 'Commands'),
            '\SigmaPHP\DB\Commands',
            [
                'DbCommand',
                'add_column_migration.php',
                'create_table_migration.php',
                'database.php',
                'migration.php',
                'model.php',
                'seeder.php',
            ]
        );

        $app->run();
    }
}
