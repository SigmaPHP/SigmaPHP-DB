<?php

namespace SigmaPHP\DB\Commands;

use SigmaPHP\DB\Commands\DbCommand;

/**
 * Create Config Command.
 */
class CreateConfig extends DbCommand
{
    /**
     * Initialize the command.
     *
     * @return void
     */
    public function init()
    {
        $this->setName('create:config');
        $this->setDescription('Create a new config file');

        $this->addOption(
            'path',
            'p',
            'The path to store the config file'
        );

        // this is will conflict with the '--path' option
        if (isset($this->options['config'])) {
            $this->removeOption('config');
        }
    }

    /**
     * Execute.
     *
     * @return void
     */
    public function execute()
    {
        $path = $this->hasOption('path') ?
            $this->getOption('path') :
            $this->getBasePath() . '/database.php';

        if ($this->filesystem->exists($path)) {
            $this->info(
                "The config file already exists"
            );

            return;
        }

        $this->filesystem->copy(
            __DIR__ . '/templates/database.php.dist',
            $path
        );

        $this->success('The config file was created successfully');
    }
}
