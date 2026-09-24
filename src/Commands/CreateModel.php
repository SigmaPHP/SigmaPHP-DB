<?php

namespace SigmaPHP\DB\Commands;

use SigmaPHP\Console\Command;
use SigmaPHP\Console\DataType;
use SigmaPHP\Console\Option;
use SigmaPHP\DB\Traits\DbConfigs;
use SigmaPHP\DB\Traits\DbConnection;
use SigmaPHP\Filesystem\Filesystem;

/**
 * Create Model Command.
 */
class CreateModel extends Command
{
    use DbConfigs, DbConnection;

    /**
     * Initialize the command.
     *
     * @return void
     */
    public function init()
    {
        $this->setName('create:model');
        $this->setDescription('Create a new model');

        $this->addArgument(
            'name',
            'The name of the model',
            DataType::STRING
        );

        $this->addOption(
            'with-migration',
            'm',
            'Generate a migration file for the model',
            Option::PARAMETER_OPTIONAL,
            DataType::BOOL,
            false
        );
    }

    /**
     * Execute.
     *
     * @return void
     */
    public function execute()
    {
        $fileName = $this->getArgument('name')->getValue();
        $configs = $this->loadConfigs(
            $this->getOption('config')->getValue()
        );

        $filesystem = new Filesystem();

        $modelsFilesPath = $this->getBasePath() . '/' .
            $configs['path_to_models'];

        if (!$filesystem->exists($modelsFilesPath)) {
            $filesystem->createDir($modelsFilesPath);
        }

        $className = ucfirst($fileName);
        $modelFile = $modelsFilesPath . '/' . $fileName . '.php';

        $filesystem->create($modelFile);
        $filesystem->write($modelFile,
            str_replace(
                '$className',
                $className,
                $filesystem->read(__DIR__ . '/templates/model.php.dist')
            )
        );

        $this->success(
            "The model '{$fileName}' was created successfully"
        );
    }
}
