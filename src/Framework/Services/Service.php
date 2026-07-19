<?php

namespace Fabiom\UglyDuckling\Framework\Services;

/**
 * Marker interface for business logic extracted out of controllers.
 * Implementations must not depend on PageStatus, superglobals, or HTTP concerns -
 * they take validated input and collaborators via the constructor and are unit-testable in isolation.
 */
interface Service {
}
