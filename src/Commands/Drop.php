<?php

namespace SigmaPHP\DB\Commands;

use SigmaPHP\DB\Commands\DbCommand;

/**
 * Drop Command.
 */
class Drop extends DbCommand
{
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

        $tables = $this->getAllTables(
            $this->configs['database_connection']['name']
        );

        foreach ($tables as $table) {
            $this->executeQuery("DROP TABLE {$table};");
            $this->writeln("Drop table {$table} was successfully completed");
        }

        $this->success("All tables were dropped successfully");
    }
}
