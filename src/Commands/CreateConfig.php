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
        // either developer provides the path or we use the root path!
        $path = $this->hasOption('path') ?
            $this->getOption('path') :
            $this->getBasePath();

        // create the path if it doesn't exists, also make sure to remove the
        // file name if it's part of the path, otherwise it will be created
        // also as a directory :)
        if (!$this->filesystem->exists($path)) {
            if (strpos($path, 'php') !== false) {
                $_path = $this->filesystem->getPath($path);

                // in case the file will be created inside the root path !
                if (!$this->filesystem->exists($_path)) {
                    $this->filesystem->createDir($_path);
                }
            } else {
                $this->filesystem->createDir($path);
            }
        }

        // add default file name 'database.php', if the provided path
        // doesn't include one
        if (strpos($path, 'php') === false) {
            $path = $this->filesystem->concatPath($path, 'database.php');
        }

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
