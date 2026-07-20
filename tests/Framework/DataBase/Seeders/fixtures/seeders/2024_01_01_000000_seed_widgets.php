<?php

use Fabiom\UglyDuckling\Framework\DataBase\Seeders\Seeder;

return new class extends Seeder {

    public function run( PDO $pdo ): void {
        $pdo->exec( "INSERT INTO widgets (name) VALUES ('bolt')" );
    }

};
