<?php

namespace SigmaPHP\DB\Commands;

use SigmaPHP\DB\Commands\DbCommand;
use SigmaPHP\Console\DataType;
use Doctrine\Inflector\InflectorFactory;

/**
 * Create Migration Command.
 */
class CreateMigration extends DbCommand
{
    /**
     * Initialize the command.
     *
     * @return void
     */
    public function init()
    {
        $this->setName('create:migration');
        $this->setDescription('Create a new database migration');

        $this->addArgument(
            'name',
            'The name of the database migration',
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
        $inflector = InflectorFactory::create()->build();

        $migrationFilesPath = $this->getBasePath() . '/' .
            $this->configs['path_to_migrations'];

        if (!$this->filesystem->exists($migrationFilesPath)) {
            $this->filesystem->createDir($migrationFilesPath);
        }

        $template = '';
        $tableName = '';
        $className = ucfirst($fileName);

        // add 'Migration' automatically if the name doesn't have it
        // and if does , then ignore
        if (stripos($fileName, 'Migration') === false) {
            $className .= 'Migration';
        }

        // prepare content
        switch ($fileName) {
            case (bool) preg_match('/Create[a-zA-Z]*Table/', $fileName):
                $tableName = $inflector->pluralize(
                    $inflector->tableize(
                        preg_replace(
                            ['/Create/', '/Table/'], '', $fileName
                        )
                    )
                );

                $template = str_replace(
                    ['$className', '$tableName'],
                    [$className, $tableName],
                    $this->filesystem->read(
                        __DIR__ . '/templates/create_table_migration.php.dist'
                    )
                );

                break;
            case (bool) preg_match(
                    '/AddColumn[a-zA-Z]*To[a-zA-Z]*Table/',
                    $fileName
                ):

                // we use this small hack to get the column and table names :)
                $migrationFileNameParts = explode('To', $fileName);

                $tableName = $inflector->pluralize(
                    $inflector->tableize(
                        preg_replace(
                            ['/Table/'], '', $migrationFileNameParts[1]
                        )
                    )
                );

                $fieldName = lcfirst(preg_replace(
                    ['/AddColumn/'], '', $migrationFileNameParts[0]
                ));

                $template = str_replace(
                    ['$className', '$tableName', '$fieldName'],
                    [$className, $tableName, $fieldName],
                    $this->filesystem->read(
                        __DIR__ . '/templates/add_column_migration.php.dist'
                    )
                );

                break;
            default:
                $template = str_replace(
                    '$className',
                    $className,
                    $this->filesystem->read(
                        __DIR__ . '/templates/migration.php.dist'
                    )
                );
        }

        // create the file and write the content
        $migrationFile = $migrationFilesPath . '/' . $className . '.php';

        if ($this->filesystem->exists($migrationFile)) {
            $this->info(
                "The migration '{$className}' already exists"
            );

            return;
        }

        $this->filesystem->create($migrationFile);
        $this->filesystem->write($migrationFile, $template);

        $this->success(
            "The migration '{$className}' was created successfully"
        );
    }
}
