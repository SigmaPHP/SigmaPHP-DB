<?php

namespace SigmaPHP\DB\Commands;

use SigmaPHP\Console\Command;
use SigmaPHP\Console\DataType;
use SigmaPHP\DB\Exceptions\InvalidConfigurationException;
use SigmaPHP\DB\Traits\DbConfigs;
use SigmaPHP\Filesystem\Filesystem;

/**
 * Create Seeder Command.
 */
class CreateSeeder extends Command
{
    use DbConfigs;

    /**
     * Initialize the command.
     *
     * @return void
     */
    public function init()
    {
        $this->setName('create:seeder');
        $this->setDescription('Create a new database seeder');

        $this->addArgument(
            'name',
            'The name of the database seeder',
            DataType::STRING
        );
    }

    /**
     * Execute.
     *
     * @return void
     */
    public function execute()
    {
        $fileName = $this->getArgument('name');
        $configs = $this->loadConfigs($this->getOption('config'));

        $filesystem = new Filesystem();

        $modelsFilesPath = $this->getBasePath() . '/' .
            $configs['path_to_seeders'];

        if (!$filesystem->exists($modelsFilesPath)) {
            $filesystem->createDir($modelsFilesPath);
        }

        $className = ucfirst($fileName);

        // add 'Seeder' automatically if the name doesn't have it
        // and if does , then ignore
        if (stripos($fileName, 'Seeder') === false) {
            $className .= 'Seeder';
        }

        $seederFile = $modelsFilesPath . '/' . $className . '.php';

        $filesystem->create($seederFile);
        $filesystem->write($seederFile,
            str_replace(
                '$className',
                $className,
                $filesystem->read(__DIR__ . '/templates/seeder.php.dist')
            )
        );

        $this->success(
            "The seeder '{$className}' was created successfully"
        );
    }
}
