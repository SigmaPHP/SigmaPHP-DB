<?php

namespace SigmaPHP\DB\Commands;

use SigmaPHP\Console\Command;
use SigmaPHP\Console\DataType;
use SigmaPHP\DB\Traits\DbConfigs;
use SigmaPHP\DB\Traits\DbConnection;

/**
 * Truncate Command.
 */
class Truncate extends Command
{
    use DbConfigs, DbConnection;

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

    }
}
