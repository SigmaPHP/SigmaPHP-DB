<?php

namespace SigmaPHP\DB\Commands;

use SigmaPHP\Console\Command;
use SigmaPHP\Console\DataType;
use SigmaPHP\DB\Traits\DbConfigs;
use SigmaPHP\DB\Traits\DbConnection;
use Doctrine\Inflector\InflectorFactory;
use SigmaPHP\Filesystem\Filesystem;

/**
 * Create Migration Command.
 */
class CreateMigration extends Command
{
    use DbConfigs;

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
        $fileName = $this->getArgument('name')->getValue();
        $configs = $this->loadConfigs(
            $this->getOption('config')->getValue()
        );

        $inflector = InflectorFactory::create()->build();
        $filesystem = new Filesystem();

        $migrationFilesPath = $this->getBasePath() . '/' .
            $configs['path_to_migrations'];

        if (!$filesystem->exists($migrationFilesPath)) {
            $filesystem->createDir($migrationFilesPath);
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
                    $filesystem->read(
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
                    $filesystem->read(
                        __DIR__ . '/templates/add_column_migration.php.dist'
                    )
                );

                break;
            default:
                $template = str_replace(
                    '$className',
                    $className,
                    $filesystem->read(__DIR__ . '/templates/migration.php.dist')
                );
        }

        // create the file and write the content
        $migrationFile = $migrationFilesPath . '/' . $fileName . '.php';

        $filesystem->create($migrationFile);
        $filesystem->write($migrationFile, $template);

        $this->success(
            "The migration file '{$fileName}' was created successfully"
        );
    }
}
