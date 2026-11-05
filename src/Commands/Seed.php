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

        $seederFilesPath = $this->getAbsolutePath(
            $this->configs['path_to_seeders']
        );

        $seeders = [];

        if ($this->hasOption('file')) {
            $seeders[] = $this->filesystem->removeExtension(
                $this->getOption('file'),
                'php'
            );
        } else {
            $seeders = $this->filesystem->list($seederFilesPath, false, false);
        }

        foreach ($seeders as $seeder) {
            require_once $this->filesystem->concatPath(
                $seederFilesPath,
                $this->filesystem->addExtension($seeder, 'php')
            );

            $seederName = $this->filesystem->getFileName($seeder);

            $seed = new $seederName($this->db());
            $seed->run();

            $this->writeln("{$seeder} was ran successfully");
        }

        $this->success("All seeders were ran successfully");
    }
}
