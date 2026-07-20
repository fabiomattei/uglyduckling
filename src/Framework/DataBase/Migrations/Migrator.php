<?php

namespace Fabiom\UglyDuckling\Framework\DataBase\Migrations;

use PDO;
use RuntimeException;

/**
 * Scans a migrations directory and applies or reverts migrations against a PDO connection,
 * tracking progress through a MigrationRepository.
 */
class Migrator {

    private PDO $pdo;
    private MigrationRepository $repository;
    private string $migrationsPath;

    public function __construct( PDO $pdo, MigrationRepository $repository, string $migrationsPath ) {
        $this->pdo = $pdo;
        $this->repository = $repository;
        $this->migrationsPath = rtrim( $migrationsPath, DIRECTORY_SEPARATOR );

        // Migration files call the static Schema facade directly, so it needs this
        // Migrator's connection before any migration's up()/down() can run.
        Schema::setConnection( $pdo );
    }

    /**
     * Runs every migration that has not run yet, in filename order.
     *
     * @return string[] names of the migrations that were run
     */
    public function migrate(): array {
        $this->repository->ensureTableExists();

        $ran = $this->repository->getRan();
        $pending = array_values( array_diff( $this->getMigrationNames(), $ran ) );

        if ( count( $pending ) === 0 ) {
            return [];
        }

        $batch = $this->repository->getNextBatchNumber();

        foreach ( $pending as $migrationName ) {
            $this->runUp( $migrationName, $batch );
        }

        return $pending;
    }

    /**
     * Reverts previously run migrations.
     *
     * With no argument, reverts every migration from the most recent batch (Laravel's
     * default `migrate:rollback` behaviour). With $steps, reverts that many individual
     * migrations, most recently run first, regardless of which batch they belong to
     * (Laravel's `migrate:rollback --step=N`).
     *
     * @return string[] names of the migrations that were rolled back, in the order reverted
     */
    public function rollback( ?int $steps = null ): array {
        $this->repository->ensureTableExists();

        $toRollback = $steps === null
            ? $this->repository->getLastBatchMigrations()
            : $this->repository->getLastMigrations( $steps );

        foreach ( $toRollback as $migrationName ) {
            $this->runDown( $migrationName );
        }

        return $toRollback;
    }

    /**
     * Reverts every migration that has ever run (via down(), most recent first), then
     * migrates again from scratch. Equivalent to Laravel's `migrate:refresh`.
     *
     * @return string[] names of the migrations that were run by the final migrate() pass
     */
    public function refresh(): array {
        $this->repository->ensureTableExists();

        foreach ( array_reverse( $this->repository->getRan() ) as $migrationName ) {
            $this->runDown( $migrationName );
        }

        return $this->migrate();
    }

    /**
     * Drops every table in the database (including the migrations tracking table itself)
     * without running any down(), then migrates from scratch. Equivalent to Laravel's
     * `migrate:fresh` - faster than refresh() but does not exercise down() migrations.
     *
     * @return string[] names of the migrations that were run by the final migrate() pass
     */
    public function fresh(): array {
        foreach ( Schema::allTableNames() as $tableName ) {
            Schema::drop( $tableName );
        }

        return $this->migrate();
    }

    /**
     * MySQL/InnoDB implicitly commits the current transaction on every DDL statement
     * (CREATE/ALTER/DROP TABLE) - unlike SQLite, which supports transactional DDL.
     * So on MySQL, as soon as a migration's up()/down() runs its first DDL statement,
     * $pdo->inTransaction() silently flips to false; calling commit()/rollBack() after
     * that throws "There is no active transaction" even though everything up to that
     * point already succeeded and was already durably written (each statement having
     * auto-committed on its own). Guarding both calls on inTransaction() makes runUp()/
     * runDown() behave correctly either way: real rollback on SQLite (or a MySQL
     * migration that never runs DDL), and a graceful no-op on MySQL once DDL has already
     * committed - at which point a mid-migration failure genuinely can't be undone, which
     * is a MySQL limitation no migration tool can code around, not a bug in this one.
     */
    private function runUp( string $migrationName, int $batch ): void {
        $migration = $this->loadMigration( $migrationName );

        $this->pdo->beginTransaction();
        try {
            $migration->up( $this->pdo );
            $this->repository->log( $migrationName, $batch );
            if ( $this->pdo->inTransaction() ) {
                $this->pdo->commit();
            }
        } catch ( \Throwable $e ) {
            if ( $this->pdo->inTransaction() ) {
                $this->pdo->rollBack();
            }
            throw $e;
        }
    }

    private function runDown( string $migrationName ): void {
        $migration = $this->loadMigration( $migrationName );

        $this->pdo->beginTransaction();
        try {
            $migration->down( $this->pdo );
            $this->repository->delete( $migrationName );
            if ( $this->pdo->inTransaction() ) {
                $this->pdo->commit();
            }
        } catch ( \Throwable $e ) {
            if ( $this->pdo->inTransaction() ) {
                $this->pdo->rollBack();
            }
            throw $e;
        }
    }

    /**
     * Returns the status of every migration found on disk: name, and whether/when it ran.
     *
     * @return array<int, array{migration: string, ran: bool}>
     */
    public function status(): array {
        $this->repository->ensureTableExists();

        $ran = $this->repository->getRan();

        $status = [];
        foreach ( $this->getMigrationNames() as $migrationName ) {
            $status[] = [
                'migration' => $migrationName,
                'ran' => in_array( $migrationName, $ran, true ),
            ];
        }

        return $status;
    }

    /**
     * @return string[] migration names (filename without extension), sorted ascending
     */
    private function getMigrationNames(): array {
        $files = glob( $this->migrationsPath . DIRECTORY_SEPARATOR . '*.php' );
        sort( $files );

        return array_map( fn( string $file ) => basename( $file, '.php' ), $files );
    }

    private function loadMigration( string $migrationName ): Migration {
        $file = $this->migrationsPath . DIRECTORY_SEPARATOR . $migrationName . '.php';

        if ( !file_exists( $file ) ) {
            throw new RuntimeException( "Migration file not found: $file" );
        }

        $migration = require $file;

        if ( !$migration instanceof Migration ) {
            throw new RuntimeException( "Migration file must return an instance of Migration: $file" );
        }

        return $migration;
    }

}
