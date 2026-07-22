<?php

namespace Fabiom\UglyDuckling\Framework\Routing;

/**
 * Cross-checks url_for() parameters against a controller's own get/post validation
 * rules, so a route's accepted parameter names are declared once (on the controller)
 * instead of duplicated in the route table.
 */
final class RouteParameterRegistry {

    private static array $cache = [];

    public static function acceptedParams(string $controllerClass): array {
        if (!isset(self::$cache[$controllerClass])) {
            $defaults = (new \ReflectionClass($controllerClass))->getDefaultProperties();
            self::$cache[$controllerClass] = array_keys(
                ($defaults['get_validation_rules'] ?? [])
                + ($defaults['post_validation_rules'] ?? [])
            );
        }
        return self::$cache[$controllerClass];
    }

}
