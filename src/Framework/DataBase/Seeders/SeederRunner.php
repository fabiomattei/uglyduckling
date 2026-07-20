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
 *
 * Each seeder file declares a named class under a fixed `Database\Seeders`
 * namespace, derived from the filename (timestamp prefix stripped, remainder
 * converted to StudlyCase) - e.g. `2026_07_20_180602_seed_authors.php` must
 * declare `Database\Seeders\SeedAuthors`. This is a fixed convention, not PSR-4:
 * the file is `require`d directly, so no composer.json autoload entry is needed
 * in the consuming application. Two seeder files whose names collide once the
 * timestamp is stripped (e.g. two "seed_authors" made on different days) would
 * declare the same class and fatal on the second `require` - keep descriptive
 * name parts unique.
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

        require_once $file;

        $className = 'Database\\Seeders\\' . self::classNameForSeeder( $seederName );

        if ( !class_exists( $className, false ) ) {
            throw new RuntimeException( "Seeder file must declare class \\$className: $file" );
        }

        $seeder = new $className();

        if ( !$seeder instanceof Seeder ) {
            throw new RuntimeException( "Seeder class \\$className must extend Seeder: $file" );
        }

        return $seeder;
    }

    /**
     * Derives the expected `Database\Seeders` class name (without namespace) from
     * a seeder's filename: strips a leading `YYYY_MM_DD_HHMMSS_` timestamp if
     * present, then converts the remaining snake_case/kebab-case name to StudlyCase.
     *
     * `seed_authors` / `2026_07_20_180602_seed_authors` -> `SeedAuthors`
     *
     * Public so `ud-migrate make-seeder` can use the same derivation when
     * scaffolding a new seeder file's class declaration.
     */
    public static function classNameForSeeder( string $seederName ): string {
        $slug = preg_replace( '/^\d{4}_\d{2}_\d{2}_\d{6}_/', '', $seederName );

        return str_replace( ' ', '', ucwords( str_replace( [ '_', '-' ], ' ', $slug ) ) );
    }

}
