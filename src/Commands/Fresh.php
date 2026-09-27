<?php

namespace SigmaPHP\DB\Commands;

use SigmaPHP\Console\Command;
use SigmaPHP\DB\Commands\Drop;
use SigmaPHP\DB\Commands\Migrate;
use SigmaPHP\DB\Commands\Seed;

/**
 * Fresh Command.
 */
class Fresh extends Command
{
    /**
     * Initialize the command.
     *
     * @return void
     */
    public function init()
    {
        $this->setName('fresh');
        $this->setDescription(
            'Drop all tables, run all migrations and seed the database'
        );
    }

    /**
     * Execute.
     *
     * @return void
     */
    public function execute()
    {
        (new Drop())->execute();
        (new Migrate())->execute();
        (new Seed())->execute();
    }
}
