<?php

use Fabiom\UglyDuckling\Framework\DataBase\Migrations\Migration;

return new class extends Migration {

    public function up( PDO $pdo ): void {
        $pdo->exec( 'CREATE TABLE gadgets (id INTEGER PRIMARY KEY)' );
        throw new RuntimeException( 'something went wrong after the DDL already ran' );
    }

    public function down( PDO $pdo ): void {
        $pdo->exec( 'DROP TABLE gadgets' );
    }

};
