<?php

namespace Fabiom\UglyDuckling\Framework\DataBase\Seeders;

use PDO;

/**
 * Base class for a single database seeder.
 *
 * A seeder file declares a named class extending this one - mirroring Laravel's
 * seeders (`class SeedAuthors extends Seeder { public function run(): void {...} }`)
 * rather than the anonymous-class convention migration files use. See SeederRunner
 * for how the class name is derived from the filename.
 */
abstract class Seeder {

    /**
     * Loads data into the database.
     */
    abstract public function run( PDO $pdo ): void;

}
