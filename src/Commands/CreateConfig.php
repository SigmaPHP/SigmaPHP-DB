<?php

namespace SigmaPHP\DB\Commands;

use SigmaPHP\Console\Command;
use SigmaPHP\Console\DataType;
use SigmaPHP\DB\Traits\DbConfigs;
use SigmaPHP\DB\Traits\DbConnection;
use SigmaPHP\Filesystem\Filesystem;

/**
 * Create Config Command.
 */
class CreateConfig extends Command
{
    use DbConfigs, DbConnection;

    /**
     * Initialize the command.
     *
     * @return void
     */
    public function init()
    {
        $this->setName('create:config');
        $this->setDescription('Create a new config file');

        $this->addArgument(
            'path',
            'The path to the config file',
            DataType::STRING
        );

        // no way we will provide path config file, while we don't have one :D
        $this->removeOption('config');
    }

    /**
     * Execute.
     *
     * @return void
     */
    public function execute()
    {
        $fileSystem = new Filesystem();

        $this->createFile(
            $path ?: $this->basePath,
            self::DEFAULT_CONFIG_FILE_NAME,
            file_get_contents(__DIR__ . '/templates/database.php.dist'),
            'Config'
        );
    }
}
