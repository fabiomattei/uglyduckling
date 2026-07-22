<?php

namespace Fabiom\UglyDuckling\Framework\Routing;

use Attribute;

/**
 * Declares that a controller or component is reachable over HTTP under the given slug.
 * Read by RouteTableGenerator to build the route table; carries no behavior itself.
 */
#[Attribute(Attribute::TARGET_CLASS)]
final class Route {

    public function __construct(
        public readonly string $name,
        public readonly string $slug,
    ) {}

}
