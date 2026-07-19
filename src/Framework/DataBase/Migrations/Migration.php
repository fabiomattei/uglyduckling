<?php

namespace Fabiom\UglyDuckling\Framework\DataBase\Migrations;

use PDO;

/**
 * Base class for a single database migration.
 *
 * A migration file returns an instance of an (usually anonymous) subclass
 * of this abstract class implementing up() and down().
 */
abstract class Migration {

    /**
     * Applies the migration.
     */
    abstract public function up( PDO $pdo ): void;

    /**
     * Reverts the migration.
     */
    abstract public function down( PDO $pdo ): void;

}
