<?php

namespace Database\Seeders;

use Fabiom\UglyDuckling\Framework\DataBase\Seeders\Seeder;
use PDO;

class SeedGadgets extends Seeder {

    public function run( PDO $pdo ): void {
        $pdo->exec( "INSERT INTO gadgets (name) VALUES ('cog')" );
    }

}
