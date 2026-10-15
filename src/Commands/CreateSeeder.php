<?php

namespace SigmaPHP\DB\Commands;

use SigmaPHP\DB\Commands\DbCommand;
use SigmaPHP\Console\DataType;

/**
 * Create Seeder Command.
 */
class CreateSeeder extends DbCommand
{
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

        $modelsFilesPath = $this->getBasePath() . '/' .
            $this->configs['path_to_seeders'];

        if (!$this->filesystem->exists($modelsFilesPath)) {
            $this->filesystem->createDir($modelsFilesPath);
        }

        $className = ucfirst($fileName);

        // add 'Seeder' automatically if the name doesn't have it
        // and if does , then ignore
        if (stripos($fileName, 'Seeder') === false) {
            $className .= 'Seeder';
        }

        $seederFile = $modelsFilesPath . '/' . $className . '.php';

        if ($this->filesystem->exists($seederFile)) {
            $this->info(
                "The seeder '{$className}' already exists"
            );

            return;
        }

        $this->filesystem->create($seederFile);
        $this->filesystem->write($seederFile,
            str_replace(
                '$className',
                $className,
                $this->filesystem->read(__DIR__ . '/templates/seeder.php.dist')
            )
        );

        $this->success(
            "The seeder '{$className}' was created successfully"
        );
    }
}
