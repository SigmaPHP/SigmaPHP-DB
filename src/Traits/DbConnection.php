<?php

namespace SigmaPHP\DB\Traits;

use SigmaPHP\DB\Connectors\Connector;
use SigmaPHP\DB\Exceptions\InvalidConfigurationException;

/**
 * DB Connection Trait.
 */
trait DbConnection
{
    /**
     * Create a new database connection instance.
     *
     * @param array $connection
     * @return \PDO
     */
    public function getDbConnection($connection)
    {
        if (empty($connection) || !is_array($connection)) {
            throw new InvalidConfigurationException(
                "Couldn't connect to database , missing/invalid configs!"
            );
        }

        return (new Connector($connection))->connect();
    }
}
