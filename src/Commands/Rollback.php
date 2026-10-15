<?php

namespace SigmaPHP\DB\Commands;

use SigmaPHP\DB\Commands\DbCommand;
use SigmaPHP\DB\Exceptions\InvalidConfigurationException;
use SigmaPHP\DB\Migrations\Logger;
use SigmaPHP\DB\Traits\DbConfigs;
use SigmaPHP\DB\Traits\DbConnection;
use SigmaPHP\DB\Traits\DbMethods;
use SigmaPHP\Filesystem\Filesystem;


/**
 * Rollback Command.
 */
class Rollback extends DbCommand
{
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
        $migrationFilesPath = $this->getBasePath() . '/' .
            $this->configs['path_to_migrations'];

        $logger = new Logger($this->db(), $this->configs['logs_table_name']);

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
                $this->configs['database_connection']['name']
            );

            $migrationClass->down();
            $logger->removeLog($migration);

            $this->writeln("{$migration} was rolled back successfully");
        }

        $this->success("Database was rolled back successfully");
    }
}
