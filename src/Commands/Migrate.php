<?php

namespace SigmaPHP\DB\Commands;

use SigmaPHP\DB\Commands\DbCommand;
use SigmaPHP\DB\Migrations\Logger;

/**
 * Migrate Command.
 */
class Migrate extends DbCommand
{
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
        $this->readConfigs();

        $migrationFilesPath = $this->getAbsolutePath(
            $this->configs['path_to_migrations']
        );

        $migrations = [];

        if ($this->hasOption('file')) {
            $migrations[] = $this->filesystem->removeExtension(
                $this->getOption('file'),
                'php'
            );
        } else {
            $migrations = $this->filesystem
                ->list($migrationFilesPath, false, false);
        }

        $logger = new Logger($this->db(), $this->configs['logs_table_name']);

        $migrations = $logger->canBeMigrated($migrations);

        if (empty($migrations)) {
            $this->info(
                "Everything is already updated, Nothing to be migrated"
            );

            return;
        }

        foreach ($migrations as $migration) {
            require_once $this->filesystem->concatPath(
                $migrationFilesPath,
                $this->filesystem->addExtension($migration, 'php')
            );

            $migrationName = $this->filesystem->getFileName($migration);

            $migrationClass = new $migrationName(
                $this->db(),
                $this->configs['database_connection']['name']
            );

            $migrationClass->up();
            $logger->log($migration);

            $this->writeln("{$migration} was migrated successfully");
        }

        $this->success("All migrations were ran successfully");
    }
}
