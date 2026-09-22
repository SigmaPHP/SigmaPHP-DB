<?php

namespace SigmaPHP\DB\Commands;

use SigmaPHP\Console\Command;
use SigmaPHP\Console\DataType;

/**
 * Create Migration Command.
 */
class CreateMigration extends Command
{
    /**
     * Initialize the command.
     *
     * @return void
     */
    public function init()
    {
        $this->setName('create:migration');
        $this->setDescription('Create a new migration file.');

        $this->addArgument(
            'name',
            'The name of the migration file.',
            DataType::STRING
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
