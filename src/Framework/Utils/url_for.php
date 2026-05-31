<?php

/**
 * Builds an HTML-safe URL from a named link definition.
 *
 * @param string $name   Key from $index_links
 * @param array  $params Query-string parameters to append
 */
function url_for(string $name, array $params = []): string {
    global $index_links;
    if (!isset($index_links[$name])) {
        throw new \InvalidArgumentException("Undefined link: '$name'");
    }
    $url = $index_links[$name]['page'] . '.html';
    if (!empty($params)) {
        $url .= '?' . http_build_query($params);
    }
    return htmlspecialchars($url);
}
