<?php

namespace SigmaPHP\DB\Commands;

use SigmaPHP\Console\Command;
use SigmaPHP\DB\Exceptions\InvalidConfigurationException;
use SigmaPHP\DB\Traits\DbConfigs;
use SigmaPHP\DB\Traits\DbConnection;
use SigmaPHP\DB\Traits\DbMethods;

/**
 * Drop Command.
 */
class Drop extends Command
{
    use DbConfigs, DbConnection;

    use DbMethods {
        execute as executeQuery;
    }

    /**
     * @var \PDO $dbConnection
     */
    private $dbConnection;

    /**
     * Initialize the command.
     *
     * @return void
     */
    public function init()
    {
        $this->setName('drop');
        $this->setDescription('Drop all tables in the database');
    }

    /**
     * Execute.
     *
     * @return void
     */
    public function execute()
    {
        if ($this->question->confirmation(
            "This command will drop all tables, please confirm"
        ) === false) {
            return;
        }

        $configs = $this->loadConfigs($this->getOption('config')->getValue());

        if (!isset($configs['database_connection']) ||
            empty($configs['database_connection'])
        ) {
            throw new InvalidConfigurationException(
                "Missing config 'database_connection'"
            );
        }

        $this->dbConnection = $this->getDbConnection(
            $configs['database_connection']
        );

        $tables = $this->getAllTables($configs['database_connection']['name']);

        foreach ($tables as $table) {
            $this->executeQuery("DROP TABLE {$table};");
            $this->writeln("Drop table {$table}; Success");
        }

        $this->success("All tables were dropped successfully");
    }
}
