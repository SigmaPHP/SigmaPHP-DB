<?php

namespace SigmaPHP\DB\Commands;

use SigmaPHP\Console\Command;
use SigmaPHP\Console\DataType;
use SigmaPHP\DB\Traits\DbConfigs;
use SigmaPHP\DB\Traits\DbConnection;

/**
 * Create Migration Command.
 */
class CreateMigration extends Command
{
    use DbConfigs, DbConnection;

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
        if (empty($fileName)) {
            throw new InvalidArgumentException("Missing name for migration");
        }

        $migrationFilesPath = $this->basePath . '/' .
            $this->configs['path_to_migrations'];

        if (!is_dir($migrationFilesPath)) {
            mkdir($migrationFilesPath, 0755, true);
        }

        $template = '';
        $tableName = '';
        $fileType = 'Migration';
        $className = ucfirst($fileName);

        // add 'Migration' automatically if the name doesn't have it
        // and if does , then ignore
        if (stripos($fileName, 'Migration') === false) {
            $className .= $fileType;
        }

        switch ($fileName) {
            case (bool) preg_match('/Create[a-zA-Z]*Table/', $fileName):
                $tableName = $this->inflector->pluralize(
                    $this->inflector->tableize(
                        preg_replace(
                            ['/Create/', '/Table/'], '', $fileName
                        )
                    )
                );

                $template = str_replace(
                    ['$className', '$tableName'],
                    [$className, $tableName],
                    file_get_contents(
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

                $tableName = $this->inflector->pluralize(
                    $this->inflector->tableize(
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
                    file_get_contents(
                        __DIR__ . '/templates/add_column_migration.php.dist'
                    )
                );

                break;
            default:
                $template = str_replace(
                    '$className',
                    $className,
                    file_get_contents(__DIR__ . '/templates/migration.php.dist')
                );
        }

        $this->createFile(
            $migrationFilesPath,
            $className,
            $template,
            $fileType
        );
    }
}
