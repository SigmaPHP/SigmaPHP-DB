<?php

namespace SigmaPHP\DB\Commands;

use SigmaPHP\Console\Command;
use SigmaPHP\Console\DataType;
use SigmaPHP\Console\Option;
use SigmaPHP\DB\Exceptions\InvalidConfigurationException;
use SigmaPHP\DB\Traits\DbConfigs;
use SigmaPHP\DB\Traits\DbConnection;
use SigmaPHP\DB\Traits\DbMethods;
use SigmaPHP\Filesystem\Filesystem;

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
     * @var array $configs
     */
    protected $configs;

    /**
     * @var Filesystem $filesystem
     */
    protected $filesystem;

    /**
     * DbCommand Constructor.
     *
     * @return void
     */
    public function __construct($options = [], $shortcuts = [])
    {
        parent::__construct($options, $shortcuts);

        $this->filesystem = new Filesystem();

        $this->addOption(
            'config',
            '',
            'Set config file path',
            Option::PARAMETER_REQUIRED,
            DataType::STRING
        );

        if ($this->hasOption('config')) {
            $this->configs = $this->loadConfigs($this->getOption('config'));
        }
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

    /**
     * Get the database's connection.
     *
     * @return \PDO
     */
    public function db()
    {
        if (!isset($this->configs['database_connection']) ||
            empty($this->configs['database_connection'])
        ) {
            throw new InvalidConfigurationException(
                "Missing config 'database_connection'"
            );
        }

        return $this->getDbConnection($this->configs['database_connection']);
    }
}
