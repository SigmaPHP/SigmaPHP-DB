<?php

namespace SigmaPHP\DB\Commands;

use SigmaPHP\Console\Command;
use SigmaPHP\DB\Exceptions\InvalidConfigurationException;
use SigmaPHP\DB\Migrations\Logger;
use SigmaPHP\DB\Traits\DbConfigs;
use SigmaPHP\DB\Traits\DbConnection;
use SigmaPHP\DB\Traits\DbMethods;
use SigmaPHP\Filesystem\Filesystem;

/**
 * Seed Command.
 */
class Seed extends Command
{
    use DbConfigs, DbConnection;

    use DbMethods {
        execute as executeQuery;
    }

    /**
     * Initialize the command.
     *
     * @return void
     */
    public function init()
    {
        $this->setName('seed');
        $this->setDescription('Seed database');

        $this->addOption(
            'file',
            'f',
            'The name of a specific seeder to run'
        );
    }

    /**
     * Execute.
     *
     * @return void
     */
    public function execute()
    {
        $configs = $this->loadConfigs($this->getOption('config')->getValue());
        $seederFilesPath = $this->getBasePath() . '/' .
            $configs['path_to_seeders'];
        $filesystem = new Filesystem();

        $seeders = [];

        if ($this->hasOption('file')) {
            // remove the file extension ".php" if exists
            $seeders[] = str_replace(
                '.php',
                '',
                $this->getOption('file')->getValue()
            );
        } else {
            $seeders = $filesystem->list($seederFilesPath, false, false);
        }

        foreach ($seeders as $seeder) {
            require_once $seederFilesPath . "/{$seeder}.php";

            // remove the sub folders in the path (if any exists)
            $parts = explode('/', $seeder);
            $seederName = $parts[count($parts) - 1];

            $seed = new $seederName($this->db());
            $seed->run();

            $this->writeln("{$seeder} was ran successfully");
        }

        $this->success("All seeders were ran successfully");
    }

    /**
     * Get the database's connection.
     *
     * @return \PDO
     */
    public function db()
    {
        $configs = $this->loadConfigs($this->getOption('config')->getValue());

        if (!isset($configs['database_connection']) ||
            empty($configs['database_connection'])
        ) {
            throw new InvalidConfigurationException(
                "Missing config 'database_connection'"
            );
        }

        return $this->getDbConnection($configs['database_connection']);
    }
}
