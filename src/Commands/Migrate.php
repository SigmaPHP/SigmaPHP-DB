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
 * Migrate Command.
 */
class Migrate extends Command
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
        $this->setName('migrate');
        $this->setDescription('Run database migrations');

        $this->addOption(
            'file',
            'f',
            'The name of a specific migration to run'
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
        $migrationFilesPath = $this->getBasePath() . '/' .
            $configs['path_to_migrations'];
        $filesystem = new Filesystem();

        $migrations = [];

        if ($this->hasOption('file')) {
            // remove the file extension ".php" if exists
            $migrations[] = str_replace(
                '.php',
                '',
                $this->getOption('file')->getValue()
            );
        } else {
            $migrations = $filesystem->list($migrationFilesPath, false, false);
        }

        $logger = new Logger($this->db(), $configs['logs_table_name']);
        $migrations = $logger->canBeMigrated($migrations);

        if (empty($migrations)) {
            $this->info(
                "Everything is already updated, Nothing to be migrated"
            );

            return;
        }

        foreach ($migrations as $migration) {
            require_once $migrationFilesPath . "/{$migration}.php";

            // remove the sub folders in the path (if any exists)
            $parts = explode('/', $migration);
            $migrationName = $parts[count($parts) - 1];

            $migrationClass = new $migrationName(
                $this->db(),
                $configs['database_connection']['name']
            );

            $migrationClass->up();
            $logger->log($migration);

            $this->writeln("{$migration} was migrated successfully");
        }

        $this->success("All migrations were ran successfully");
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
