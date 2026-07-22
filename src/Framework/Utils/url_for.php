<?php

use Fabiom\UglyDuckling\Framework\Routing\RouteParameterRegistry;
use Fabiom\UglyDuckling\Framework\Routing\RouteTable;

/**
 * Builds an HTML-safe URL from a named route registered in the RouteTable.
 * For controller routes, $params keys are cross-checked against the target
 * controller's own get/post validation rules to catch stale/typo'd names early.
 *
 * @param string $name   Route name
 * @param array  $params Query-string parameters to append
 */
function url_for(string $name, array $params = []): string {
    $route = RouteTable::get($name);

    if (isset($route['controller'])) {
        $unknown = array_diff(array_keys($params), RouteParameterRegistry::acceptedParams($route['controller']));
        if ($unknown !== []) {
            throw new \InvalidArgumentException(
                "url_for('$name'): unknown parameter(s) " . implode(', ', $unknown)
                . ' — not in ' . $route['controller'] . "'s validation rules"
            );
        }
    }

    $url = $route['slug'] . '.html';
    if (!empty($params)) {
        $url .= '?' . http_build_query($params);
    }
    return htmlspecialchars($url);
}
