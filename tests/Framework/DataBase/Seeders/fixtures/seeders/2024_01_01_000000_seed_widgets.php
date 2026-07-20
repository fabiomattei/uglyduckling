<?php

namespace Database\Seeders;

use Fabiom\UglyDuckling\Framework\DataBase\Seeders\Seeder;
use PDO;

class SeedWidgets extends Seeder {

    public function run( PDO $pdo ): void {
        $pdo->exec( "INSERT INTO widgets (name) VALUES ('bolt')" );
    }

}
