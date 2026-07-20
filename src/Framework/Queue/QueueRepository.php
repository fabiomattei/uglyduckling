<?php

namespace Fabiom\UglyDuckling\Framework\Queue;

use PDO;
use stdClass;

/**
 * Owns the `queue_jobs` table: pushing jobs, reserving the next one for a
 * worker, and recording completion, failure, or release-for-retry.
 */
class QueueRepository {

    const TABLE_NAME = 'queue_jobs';

    private PDO $pdo;

    public function __construct( PDO $pdo ) {
        $this->pdo = $pdo;
    }

    /**
     * Creates the queue table if it does not exist yet.
     */
    public function ensureTableExists(): void {
        // SQLite (used in tests) spells the keyword without an underscore; production runs on MySQL.
        $autoIncrementKeyword = $this->pdo->getAttribute( PDO::ATTR_DRIVER_NAME ) === 'sqlite'
            ? 'AUTOINCREMENT'
            : 'AUTO_INCREMENT';

        $this->pdo->exec(
            'CREATE TABLE IF NOT EXISTS ' . self::TABLE_NAME . ' (
                id INTEGER PRIMARY KEY ' . $autoIncrementKeyword . ',
                queue VARCHAR(255) NOT NULL,
                job_class VARCHAR(255) NOT NULL,
                payload TEXT NOT NULL,
                attempts INTEGER NOT NULL DEFAULT 0,
                max_attempts INTEGER NOT NULL DEFAULT 3,
                available_at DATETIME NOT NULL,
                reserved_at DATETIME NULL,
                failed_at DATETIME NULL,
                error TEXT NULL,
                created_at DATETIME NOT NULL
            )'
        );
    }

    /**
     * Enqueues a job, returning the id of the row it was stored in.
     */
    public function push( string $queue, string $jobClass, array $payload, int $delaySeconds = 0, int $maxAttempts = 3 ): int {
        if ( !is_subclass_of( $jobClass, QueueJob::class ) ) {
            throw new \InvalidArgumentException( "$jobClass must extend " . QueueJob::class );
        }

        $now = $this->now();
        $availableAt = date( 'Y-m-d H:i:s', time() + $delaySeconds );

        $statement = $this->pdo->prepare(
            'INSERT INTO ' . self::TABLE_NAME . '
                (queue, job_class, payload, max_attempts, available_at, created_at)
             VALUES (:queue, :job_class, :payload, :max_attempts, :available_at, :created_at)'
        );
        $statement->bindParam( ':queue', $queue );
        $statement->bindParam( ':job_class', $jobClass );
        $payloadJson = json_encode( $payload );
        $statement->bindParam( ':payload', $payloadJson );
        $statement->bindParam( ':max_attempts', $maxAttempts, PDO::PARAM_INT );
        $statement->bindParam( ':available_at', $availableAt );
        $statement->bindParam( ':created_at', $now );
        $statement->execute();

        return (int) $this->pdo->lastInsertId();
    }

    /**
     * Reserves and returns the next available job for the given queue, or null
     * if there is none. Reservation is a SELECT-then-guarded-UPDATE, not a
     * single atomic operation: under concurrent workers this can occasionally
     * cost a wasted attempt (the UPDATE affects 0 rows), but it never lets two
     * workers reserve the same job.
     */
    public function reserveNext( string $queue ): ?stdClass {
        $now = $this->now();

        $select = $this->pdo->prepare(
            'SELECT id FROM ' . self::TABLE_NAME . '
             WHERE queue = :queue AND reserved_at IS NULL AND failed_at IS NULL AND available_at <= :now
             ORDER BY id ASC LIMIT 1'
        );
        $select->bindParam( ':queue', $queue );
        $select->bindParam( ':now', $now );
        $select->execute();

        $id = $select->fetchColumn();
        if ( $id === false ) {
            return null;
        }

        $reserve = $this->pdo->prepare(
            'UPDATE ' . self::TABLE_NAME . ' SET reserved_at = :now WHERE id = :id AND reserved_at IS NULL'
        );
        $reserve->bindParam( ':now', $now );
        $reserve->bindParam( ':id', $id, PDO::PARAM_INT );
        $reserve->execute();

        if ( $reserve->rowCount() === 0 ) {
            return null;
        }

        $fetch = $this->pdo->prepare( 'SELECT * FROM ' . self::TABLE_NAME . ' WHERE id = :id' );
        $fetch->bindParam( ':id', $id, PDO::PARAM_INT );
        $fetch->execute();
        $fetch->setFetchMode( PDO::FETCH_OBJ );

        $job = $fetch->fetch();
        return $job === false ? null : $job;
    }

    /**
     * Removes a job that finished successfully.
     */
    public function markCompleted( int $id ): void {
        $statement = $this->pdo->prepare( 'DELETE FROM ' . self::TABLE_NAME . ' WHERE id = :id' );
        $statement->bindParam( ':id', $id, PDO::PARAM_INT );
        $statement->execute();
    }

    /**
     * Marks a job as permanently failed (attempts exhausted), keeping the row
     * for inspection instead of deleting it.
     */
    public function markFailed( int $id, string $error ): void {
        $now = $this->now();

        $statement = $this->pdo->prepare(
            'UPDATE ' . self::TABLE_NAME . '
             SET reserved_at = NULL, failed_at = :now, error = :error
             WHERE id = :id'
        );
        $statement->bindParam( ':now', $now );
        $statement->bindParam( ':error', $error );
        $statement->bindParam( ':id', $id, PDO::PARAM_INT );
        $statement->execute();
    }

    /**
     * Releases a job back to the queue for another attempt after $delaySeconds.
     */
    public function release( int $id, int $delaySeconds ): void {
        $availableAt = date( 'Y-m-d H:i:s', time() + $delaySeconds );

        $statement = $this->pdo->prepare(
            'UPDATE ' . self::TABLE_NAME . '
             SET reserved_at = NULL, attempts = attempts + 1, available_at = :available_at
             WHERE id = :id'
        );
        $statement->bindParam( ':available_at', $availableAt );
        $statement->bindParam( ':id', $id, PDO::PARAM_INT );
        $statement->execute();
    }

    public function countPending( string $queue ): int {
        $statement = $this->pdo->prepare(
            'SELECT COUNT(*) FROM ' . self::TABLE_NAME . ' WHERE queue = :queue AND failed_at IS NULL'
        );
        $statement->bindParam( ':queue', $queue );
        $statement->execute();

        return (int) $statement->fetchColumn();
    }

    public function countFailed( string $queue ): int {
        $statement = $this->pdo->prepare(
            'SELECT COUNT(*) FROM ' . self::TABLE_NAME . ' WHERE queue = :queue AND failed_at IS NOT NULL'
        );
        $statement->bindParam( ':queue', $queue );
        $statement->execute();

        return (int) $statement->fetchColumn();
    }

    private function now(): string {
        return date( 'Y-m-d H:i:s', time() );
    }

}
