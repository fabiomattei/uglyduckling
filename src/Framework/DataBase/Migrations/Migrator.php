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
            $migration = $this->loadMigration( $migrationName );

            $this->pdo->beginTransaction();
            try {
                $migration->up( $this->pdo );
                $this->repository->log( $migrationName, $batch );
                $this->pdo->commit();
            } catch ( \Throwable $e ) {
                $this->pdo->rollBack();
                throw $e;
            }
        }

        return $pending;
    }

    /**
     * Reverts every migration that ran in the most recent batch, in reverse order.
     *
     * @return string[] names of the migrations that were rolled back
     */
    public function rollback(): array {
        $this->repository->ensureTableExists();

        $toRollback = $this->repository->getLastBatchMigrations();

        foreach ( $toRollback as $migrationName ) {
            $migration = $this->loadMigration( $migrationName );

            $this->pdo->beginTransaction();
            try {
                $migration->down( $this->pdo );
                $this->repository->delete( $migrationName );
                $this->pdo->commit();
            } catch ( \Throwable $e ) {
                $this->pdo->rollBack();
                throw $e;
            }
        }

        return $toRollback;
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
