<?php

namespace SigmaPHP\DB\Commands;

use Override;
use SigmaPHP\Console\Command;
use SigmaPHP\DB\Exceptions\InvalidConfigurationException;
use SigmaPHP\DB\Migrations\Logger;
use SigmaPHP\DB\Traits\DbConfigs;
use SigmaPHP\DB\Traits\DbConnection;
use SigmaPHP\DB\Traits\DbMethods;

/**
 * DB Base Command Class
 */
abstract class DbCommand extends Command
{
    use DbConfigs, DbConnection;

    use DbMethods {
        execute as executeQuery;
    }

    /**
     * @var \PDO $dbConnection
     */
    private $dbConnection;

    /**
     * DbCommand Constructor.
     *
     * @return void
     */
    public function __construct($options = [], $shortcuts = [])
    {
        parent::__construct($options, $shortcuts);
    }

    /**
     * Initialize the command.
     *
     * @return void
     */
    abstract public function init();

    /**
     * Execute.
     *
     * @return void
     */
    abstract public function execute();
}
