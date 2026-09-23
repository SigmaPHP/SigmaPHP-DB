<?php

namespace SigmaPHP\DB\Commands;

use SigmaPHP\Console\Command;
use SigmaPHP\Console\DataType;
use SigmaPHP\DB\Traits\DbConfigs;
use SigmaPHP\DB\Traits\DbConnection;

/**
 * Rollback Command.
 */
class Rollback extends Command
{
    use DbConfigs, DbConnection;

    /**
     * Initialize the command.
     *
     * @return void
     */
    public function init()
    {
        $this->setName('rollback');
        $this->setDescription('Rollback database migrations');

        $this->addOption(
            'date',
            'd',
            'Provide a specific date to rollback to'
        );
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
