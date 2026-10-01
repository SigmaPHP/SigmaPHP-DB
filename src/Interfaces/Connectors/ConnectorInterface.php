<?php

namespace SigmaPHP\DB\Interfaces\Connectors;

/**
 * Connector Interface
 */
interface ConnectorInterface
{
    /**
     * Create new PDO connection.
     *
     * @return \PDO
     */
    public function connect();
}
