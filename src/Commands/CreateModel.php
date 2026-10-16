<?php

namespace SigmaPHP\DB\Commands;

use SigmaPHP\DB\Commands\DbCommand;
use SigmaPHP\DB\Commands\CreateMigration;
use SigmaPHP\Console\DataType;
use SigmaPHP\Console\Option;
use Doctrine\Inflector\InflectorFactory;

/**
 * Create Model Command.
 */
class CreateModel extends DbCommand
{
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
            Option::PARAMETER_NONE,
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
        $this->readConfigs();

        $fileName = $this->getArgument('name');

        $modelsFilesPath = $this->getBasePath() . '/' .
            $this->configs['path_to_models'];

        if (!$this->filesystem->exists($modelsFilesPath)) {
            $this->filesystem->createDir($modelsFilesPath);
        }

        $className = ucfirst($fileName);
        $modelFile = $modelsFilesPath . '/' . $className . '.php';

        if ($this->filesystem->exists($modelFile)) {
            $this->info(
                "The model '{$className}' already exists"
            );

            return;
        }

        $this->filesystem->create($modelFile);
        $this->filesystem->write($modelFile,
            str_replace(
                '$className',
                $className,
                $this->filesystem->read(__DIR__ . '/templates/model.php.dist')
            )
        );

        $this->success(
            "The model '{$className}' was created successfully"
        );

        // handle migration creation
        if ($this->hasOption('with-migration')) {
            $inflector = InflectorFactory::create()->build();
            $migrationFileName = $inflector->pluralize($fileName);

            $createMigrationCommand = new CreateMigration($this->options);

            $createMigrationCommand->getArguments()['name']->setValue(
                "Create{$migrationFileName}Table"
            );

            $createMigrationCommand->setIOHandler($this->io);
            $createMigrationCommand->execute();
        }
    }
}
