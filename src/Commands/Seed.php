<?php

namespace SigmaPHP\DB\Commands;

use SigmaPHP\Console\Command;
use SigmaPHP\Console\DataType;
use SigmaPHP\DB\Traits\DbConfigs;
use SigmaPHP\DB\Traits\DbConnection;

/**
 * Seed Command.
 */
class Seed extends Command
{
    use DbConfigs, DbConnection;

    /**
     * Initialize the command.
     *
     * @return void
     */
    public function init()
    {
        $this->setName('seed');
        $this->setDescription('Seed database');

        $this->addOption(
            'file',
            'f',
            'The name of a specific seeder to run'
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
