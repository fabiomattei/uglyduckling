<?php

use Fabiom\UglyDuckling\Framework\DataBase\Migrations\Migration;

return new class extends Migration {

    public function up( PDO $pdo ): void {
        $pdo->exec( 'CREATE TABLE gadgets (id INTEGER PRIMARY KEY, name VARCHAR(255))' );
    }

    public function down( PDO $pdo ): void {
        $pdo->exec( 'DROP TABLE gadgets' );
    }

};
