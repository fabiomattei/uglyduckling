<?php

namespace Fabiom\UglyDuckling\Framework\DataBase\Seeders;

use PDO;

/**
 * Base class for a single database seeder.
 *
 * A seeder file returns an instance of an (usually anonymous) subclass
 * of this abstract class implementing run().
 */
abstract class Seeder {

    /**
     * Loads data into the database.
     */
    abstract public function run( PDO $pdo ): void;

}
