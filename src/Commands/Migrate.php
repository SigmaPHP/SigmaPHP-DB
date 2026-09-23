<?php

namespace SigmaPHP\DB\Commands;

use SigmaPHP\Console\Command;
use SigmaPHP\Console\DataType;
use SigmaPHP\DB\Traits\DbConfigs;
use SigmaPHP\DB\Traits\DbConnection;

/**
 * Migrate Command.
 */
class Migrate extends Command
{
    use DbConfigs, DbConnection;

    /**
     * Initialize the command.
     *
     * @return void
     */
    public function init()
    {
        $this->setName('migrate');
        $this->setDescription('Run database migrations');

        $this->addOption(
            'file',
            'f',
            'The name of a specific migration to run'
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
