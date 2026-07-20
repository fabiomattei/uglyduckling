<?php

namespace Fabiom\UglyDuckling\Framework\DataBase\Seeders;

use PDO;
use RuntimeException;

/**
 * Scans a seeders directory and runs seeder files against a PDO connection.
 *
 * Unlike migrations, seeders are not tracked as "already run" - they are
 * expected to be re-runnable (e.g. via insertOrIgnore/truncate-then-insert
 * inside run()), matching Laravel's db:seed semantics.
 */
class SeederRunner {

    private PDO $pdo;
    private string $seedersPath;

    public function __construct( PDO $pdo, string $seedersPath ) {
        $this->pdo = $pdo;
        $this->seedersPath = rtrim( $seedersPath, DIRECTORY_SEPARATOR );
    }

    /**
     * Runs every seeder found in the directory, in filename order.
     *
     * @return string[] names of the seeders that were run
     */
    public function run(): array {
        $names = $this->getSeederNames();

        foreach ( $names as $name ) {
            $this->runOne( $name );
        }

        return $names;
    }

    /**
     * Runs a single seeder by name (filename without extension).
     */
    public function runOne( string $seederName ): void {
        $seeder = $this->loadSeeder( $seederName );

        $this->pdo->beginTransaction();
        try {
            $seeder->run( $this->pdo );
            $this->pdo->commit();
        } catch ( \Throwable $e ) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    /**
     * @return string[] seeder names (filename without extension), sorted ascending
     */
    private function getSeederNames(): array {
        $files = glob( $this->seedersPath . DIRECTORY_SEPARATOR . '*.php' );
        sort( $files );

        return array_map( fn( string $file ) => basename( $file, '.php' ), $files );
    }

    private function loadSeeder( string $seederName ): Seeder {
        $file = $this->seedersPath . DIRECTORY_SEPARATOR . $seederName . '.php';

        if ( !file_exists( $file ) ) {
            throw new RuntimeException( "Seeder file not found: $file" );
        }

        $seeder = require $file;

        if ( !$seeder instanceof Seeder ) {
            throw new RuntimeException( "Seeder file must return an instance of Seeder: $file" );
        }

        return $seeder;
    }

}
