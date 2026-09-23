<?php

namespace SigmaPHP\DB\Commands;

use SigmaPHP\Console\Command;
use SigmaPHP\Console\DataType;
use SigmaPHP\DB\Traits\DbConfigs;
use SigmaPHP\DB\Traits\DbConnection;

/**
 * Drop Command.
 */
class Drop extends Command
{
    use DbConfigs, DbConnection;

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

    }
}
