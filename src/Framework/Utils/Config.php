<?php

namespace Fabiom\UglyDuckling\Framework\Utils;

/**
 * Class Config
 * @package Fabiom\UglyDuckling\Framework\Utils
 *
 * Single access point for application configuration, defined by the
 * consuming app as PHP constants (e.g. BASE_PATH, PATH_TO_APP,
 * APPLICATION_ENVIRONMENT) in its bootstrap.
 */
class Config {

    /**
     * @return mixed value of the constant, or $default if it is not defined
     */
    static public function get(string $key, mixed $default = null): mixed {
        return defined($key) ? constant($key) : $default;
    }

    /**
     * @throws \RuntimeException if the constant is not defined
     */
    static public function required(string $key): mixed {
        if ( !defined($key) ) {
            throw new \RuntimeException("Missing required config value: {$key}");
        }
        return constant($key);
    }

    static public function has(string $key): bool {
        return defined($key);
    }

}
