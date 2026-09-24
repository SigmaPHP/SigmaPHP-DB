<?php

namespace SigmaPHP\DB\Commands;

use SigmaPHP\Console\Command;
use SigmaPHP\DB\Traits\DbConfigs;
use SigmaPHP\Filesystem\Filesystem;

/**
 * Create Config Command.
 */
class CreateConfig extends Command
{
    use DbConfigs;

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
        $this->removeOption('config');
    }

    /**
     * Execute.
     *
     * @return void
     */
    public function execute()
    {
        $filesystem = new Filesystem();

        $path = $this->getBasePath() . '/database.php';

        if ($this->hasOption('path')) {
            $path = $this->getOption('path')->getValue();
        }

        $filesystem->copy(
            __DIR__ . '/templates/database.php.dist',
            $path
        );

        $this->success('The config file was created successfully');
    }
}
