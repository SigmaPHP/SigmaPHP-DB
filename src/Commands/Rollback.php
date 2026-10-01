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
 * Rollback Command.
 */
class Rollback extends Command
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
        $this->setName('rollback');
        $this->setDescription('Rollback database migrations');

        $this->addOption(
            'date',
            'd',
            'Provide a specific date to rollback to'
        );
    }

    /**
     * Execute.
     *
     * @return void
     */
    public function execute()
    {
        $configs = $this->loadConfigs($this->getOption('config'));
        $migrationFilesPath = $this->getBasePath() . '/' .
            $configs['path_to_migrations'];

        $logger = new Logger($this->db(), $configs['logs_table_name']);
        $migrations = $logger->canBeRolledBack(
            $this->getOption('date')
        );

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

            $migrationClass->down();
            $logger->removeLog($migration);

            $this->writeln("{$migration} was rolled back successfully");
        }

        $this->success("Database was rolled back successfully");
    }

    /**
     * Get the database's connection.
     *
     * @return \PDO
     */
    public function db()
    {
        $configs = $this->loadConfigs($this->getOption('config'));

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
