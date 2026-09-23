<?php

namespace SigmaPHP\DB\Commands;

use SigmaPHP\Console\Command;
use SigmaPHP\Console\DataType;
use SigmaPHP\Console\Option;
use SigmaPHP\DB\Traits\DbConfigs;
use SigmaPHP\DB\Traits\DbConnection;

/**
 * Create Model Command.
 */
class CreateModel extends Command
{
    use DbConfigs, DbConnection;

    /**
     * Initialize the command.
     *
     * @return void
     */
    public function init()
    {
        $this->setName('create:model');
        $this->setDescription('Create a new model');

        $this->addArgument(
            'name',
            'The name of the model',
            DataType::STRING
        );

        $this->addOption(
            'with-migration',
            'm',
            'Generate a migration file for the model',
            Option::PARAMETER_OPTIONAL,
            DataType::BOOL,
            false
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
