<?php

use Fabiom\UglyDuckling\Framework\DataBase\Migrations\MigrationRepository;

/**
 *  Testing the MigrationRepository class
 */
class MigrationRepositoryTest extends PHPUnit\Framework\TestCase {

    private MigrationRepository $repository;

    protected function setUp(): void {
        $pdo = new PDO( 'sqlite::memory:' );
        $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

        $this->repository = new MigrationRepository( $pdo );
        $this->repository->ensureTableExists();
    }

    public function testEnsureTableExistsIsIdempotent() {
        $this->repository->ensureTableExists();
        $this->assertEquals( [], $this->repository->getRan() );
    }

    public function testLogAddsAMigrationToTheRanList() {
        $this->repository->log( '2024_01_01_000000_create_widgets_table', 1 );

        $this->assertEquals( [ '2024_01_01_000000_create_widgets_table' ], $this->repository->getRan() );
    }

    public function testGetRanPreservesInsertionOrder() {
        $this->repository->log( '2024_01_01_000000_create_widgets_table', 1 );
        $this->repository->log( '2024_01_02_000000_create_gadgets_table', 1 );

        $this->assertEquals(
            [ '2024_01_01_000000_create_widgets_table', '2024_01_02_000000_create_gadgets_table' ],
            $this->repository->getRan()
        );
    }

    public function testGetNextBatchNumberStartsAtOne() {
        $this->assertEquals( 1, $this->repository->getNextBatchNumber() );
    }

    public function testGetNextBatchNumberIncrementsAfterABatchRuns() {
        $this->repository->log( '2024_01_01_000000_create_widgets_table', 1 );

        $this->assertEquals( 2, $this->repository->getNextBatchNumber() );
    }

    public function testGetLastBatchMigrationsReturnsThemInReverseOrder() {
        $this->repository->log( '2024_01_01_000000_create_widgets_table', 1 );
        $this->repository->log( '2024_01_02_000000_create_gadgets_table', 1 );

        $this->assertEquals(
            [ '2024_01_02_000000_create_gadgets_table', '2024_01_01_000000_create_widgets_table' ],
            $this->repository->getLastBatchMigrations()
        );
    }

    public function testGetLastBatchMigrationsOnlyReturnsTheMostRecentBatch() {
        $this->repository->log( '2024_01_01_000000_create_widgets_table', 1 );
        $this->repository->log( '2024_01_02_000000_create_gadgets_table', 2 );

        $this->assertEquals(
            [ '2024_01_02_000000_create_gadgets_table' ],
            $this->repository->getLastBatchMigrations()
        );
    }

    public function testGetLastMigrationsReturnsTheMostRecentNRegardlessOfBatch() {
        $this->repository->log( '2024_01_01_000000_create_widgets_table', 1 );
        $this->repository->log( '2024_01_02_000000_create_gadgets_table', 2 );
        $this->repository->log( '2024_01_03_000000_create_gizmos_table', 3 );

        $this->assertEquals(
            [ '2024_01_03_000000_create_gizmos_table', '2024_01_02_000000_create_gadgets_table' ],
            $this->repository->getLastMigrations( 2 )
        );
    }

    public function testDeleteRemovesAMigrationFromTheRanList() {
        $this->repository->log( '2024_01_01_000000_create_widgets_table', 1 );
        $this->repository->delete( '2024_01_01_000000_create_widgets_table' );

        $this->assertEquals( [], $this->repository->getRan() );
    }

}
