<?php

namespace SigmaPHP\DB\Commands;

use SigmaPHP\Console\Command;
use SigmaPHP\DB\Exceptions\InvalidConfigurationException;
use SigmaPHP\DB\Traits\DbConfigs;
use SigmaPHP\DB\Traits\DbConnection;
use SigmaPHP\DB\Traits\DbMethods;

/**
 * Truncate Command.
 */
class Truncate extends Command
{
    use DbConfigs, DbConnection;

    use DbMethods {
        execute as executeQuery;
    }

    /**
     * Initialize the command.
     *
     * @return void
     */
    public function init()
    {
        $this->setName('truncate');
        $this->setDescription('Delete the data in all tables');
    }

    /**
     * Execute.
     *
     * @return void
     */
    public function execute()
    {
        if ($this->question->confirmation(
            "This command will truncate all tables, please confirm"
        ) === false) {
            return;
        }

        $configs = $this->loadConfigs($this->getOption('config'));
        $tables = $this->getAllTables($configs['database_connection']['name']);

        foreach ($tables as $table) {
            if ($table == $configs['logs_table_name']) {
                continue;
            }

            $this->executeQuery("TRUNCATE TABLE {$table};");
            $this->writeln(
                "Truncate table {$table} was successfully completed"
            );
        }

        $this->success("All tables were truncated successfully");
    }

    /**
     * Get the database's connection.
     *
     * @return \PDO
     */
    public function db()
    {
        $configs = $this->loadConfigs($this->getOption('config'));

        if (!isset($configs['database_connection']) ||
            empty($configs['database_connection'])
        ) {
            throw new InvalidConfigurationException(
                "Missing config 'database_connection'"
            );
        }

        return $this->getDbConnection($configs['database_connection']);
    }
}
