<?php

namespace SigmaPHP\DB\Connectors;

use SigmaPHP\DB\Interfaces\Connectors\ConnectorInterface;

/**
 * Connector Class
 */
class Connector implements ConnectorInterface
{
    /**
     * @var array $configs
     */
    private $configs;

    /**
     * @var array $options
     */
    private $options;

    /**
     * Connector Constructor
     *
     * @param array $configs
     * @param array $options
     */
    public function __construct($configs, $options = [])
    {
        $this->configs = $configs;
        $this->options = $options;
    }

    /**
     * Create new PDO connection.
     *
     * @return \PDO
     */
    public function connect()
    {
        return new \PDO(
            "mysql:host={$this->configs['host']};
            dbname={$this->configs['name']};
            port={$this->configs['port']}",
            $this->configs['user'],
            $this->configs['pass'],
            $this->options
        );
    }
}
