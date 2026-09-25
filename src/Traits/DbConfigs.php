<?php

namespace SigmaPHP\DB\Traits;

use SigmaPHP\DB\Exceptions\InvalidConfigurationException;
use SigmaPHP\DB\Traits\HelperMethods;

/**
 * DB Configs Trait.
 */
trait DbConfigs
{
    use HelperMethods;

    /**
     * Load config.
     *
     * @param string $path
     * @return array
     */
    public function loadConfigs($path = '')
    {
        $configFilePath = $this->getBasePath() . '/database.php';

        if (!file_exists($configFilePath)) {
            throw new InvalidConfigurationException(
                "Config file '{$configFilePath}' doesn't exist"
            );
        }

        $configs = require $configFilePath;

        // replace ./ with empty string if exists in the path
        $configs['path_to_migrations'] =
            str_replace('./', '', $configs['path_to_migrations']);

        $configs['path_to_seeders'] =
            str_replace('./', '', $configs['path_to_seeders']);

        $configs['path_to_models'] =
            str_replace('./', '', $configs['path_to_models']);

        return $configs;
    }
}
