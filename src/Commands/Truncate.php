<?php

namespace SigmaPHP\DB\Commands;

use SigmaPHP\Db\Commands\DbCommand;
use SigmaPHP\DB\Exceptions\InvalidConfigurationException;
use SigmaPHP\DB\Traits\DbConfigs;
use SigmaPHP\DB\Traits\DbConnection;
use SigmaPHP\DB\Traits\DbMethods;

/**
 * Truncate Command.
 */
class Truncate extends DbCommand
{
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

        $tables = $this->getAllTables(
            $this->configs['database_connection']['name']
        );

        foreach ($tables as $table) {
            if ($table == $this->configs['logs_table_name']) {
                continue;
            }

            $this->executeQuery("TRUNCATE TABLE {$table};");
            $this->writeln(
                "Truncate table {$table} was successfully completed"
            );
        }

        $this->success("All tables were truncated successfully");
    }
}
