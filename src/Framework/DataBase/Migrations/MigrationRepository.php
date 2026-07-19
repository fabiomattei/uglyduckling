<?php

namespace Fabiom\UglyDuckling\Framework\DataBase\Migrations;

use PDO;

/**
 * Owns the `migrations` tracking table: which migrations have run and in which batch.
 */
class MigrationRepository {

    const TABLE_NAME = 'migrations';

    private PDO $pdo;

    public function __construct( PDO $pdo ) {
        $this->pdo = $pdo;
    }

    /**
     * Creates the tracking table if it does not exist yet.
     */
    public function ensureTableExists(): void {
        // SQLite (used in tests) spells the keyword without an underscore; production runs on MySQL.
        $autoIncrementKeyword = $this->pdo->getAttribute( PDO::ATTR_DRIVER_NAME ) === 'sqlite'
            ? 'AUTOINCREMENT'
            : 'AUTO_INCREMENT';

        $this->pdo->exec(
            'CREATE TABLE IF NOT EXISTS ' . self::TABLE_NAME . ' (
                id INTEGER PRIMARY KEY ' . $autoIncrementKeyword . ',
                migration VARCHAR(255) NOT NULL,
                batch INTEGER NOT NULL
            )'
        );
    }

    /**
     * Returns the names of every migration that has already run, in the order they ran.
     *
     * @return string[]
     */
    public function getRan(): array {
        $statement = $this->pdo->query( 'SELECT migration FROM ' . self::TABLE_NAME . ' ORDER BY id ASC' );
        return $statement->fetchAll( PDO::FETCH_COLUMN );
    }

    /**
     * Returns the names of the migrations that ran in the most recent batch, in reverse order
     * (last one run first), ready to be rolled back.
     *
     * @return string[]
     */
    public function getLastBatchMigrations(): array {
        $lastBatch = $this->getLastBatchNumber();
        if ( $lastBatch === 0 ) {
            return [];
        }

        $statement = $this->pdo->prepare(
            'SELECT migration FROM ' . self::TABLE_NAME . ' WHERE batch = :batch ORDER BY id DESC'
        );
        $statement->bindParam( ':batch', $lastBatch, PDO::PARAM_INT );
        $statement->execute();

        return $statement->fetchAll( PDO::FETCH_COLUMN );
    }

    public function getNextBatchNumber(): int {
        return $this->getLastBatchNumber() + 1;
    }

    public function log( string $migration, int $batch ): void {
        $statement = $this->pdo->prepare(
            'INSERT INTO ' . self::TABLE_NAME . ' (migration, batch) VALUES (:migration, :batch)'
        );
        $statement->bindParam( ':migration', $migration );
        $statement->bindParam( ':batch', $batch, PDO::PARAM_INT );
        $statement->execute();
    }

    public function delete( string $migration ): void {
        $statement = $this->pdo->prepare(
            'DELETE FROM ' . self::TABLE_NAME . ' WHERE migration = :migration'
        );
        $statement->bindParam( ':migration', $migration );
        $statement->execute();
    }

    private function getLastBatchNumber(): int {
        $statement = $this->pdo->query( 'SELECT MAX(batch) FROM ' . self::TABLE_NAME );
        $result = $statement->fetchColumn();
        return $result === null ? 0 : (int) $result;
    }

}
