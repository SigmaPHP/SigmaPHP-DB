<?php

namespace SigmaPHP\DB\Commands;

use SigmaPHP\DB\Commands\DbCommand;

/**
 * Seed Command.
 */
class Seed extends DbCommand
{
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
        $this->readConfigs();

        $seederFilesPath = $this->getBasePath() . '/' .
            $this->configs['path_to_seeders'];

        $seeders = [];

        if ($this->hasOption('file')) {
            // remove the file extension ".php" if exists
            $seeders[] = str_replace('.php', '', $this->getOption('file'));
        } else {
            $seeders = $this->filesystem->list($seederFilesPath, false, false);
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
}
