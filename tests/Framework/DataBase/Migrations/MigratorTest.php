<?php

use Fabiom\UglyDuckling\Framework\DataBase\Migrations\MigrationRepository;
use Fabiom\UglyDuckling\Framework\DataBase\Migrations\Migrator;

/**
 *  Testing the Migrator class against real migration fixture files and an in-memory SQLite database
 */
class MigratorTest extends PHPUnit\Framework\TestCase {

    private PDO $pdo;
    private Migrator $migrator;

    protected function setUp(): void {
        $this->pdo = new PDO( 'sqlite::memory:' );
        $this->pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

        $this->migrator = new Migrator( $this->pdo, new MigrationRepository( $this->pdo ), __DIR__ . '/fixtures/migrations' );
    }

    public function testMigrateRunsEveryPendingMigrationInFilenameOrder() {
        $ran = $this->migrator->migrate();

        $this->assertEquals(
            [ '2024_01_01_000000_create_widgets_table', '2024_01_02_000000_create_gadgets_table' ],
            $ran
        );
        $this->assertTableExists( 'widgets' );
        $this->assertTableExists( 'gadgets' );
    }

    public function testMigrateIsIdempotent() {
        $this->migrator->migrate();

        $this->assertEquals( [], $this->migrator->migrate() );
    }

    public function testStatusReflectsWhichMigrationsHaveRun() {
        $this->migrator->migrate();

        $this->assertEquals(
            [
                [ 'migration' => '2024_01_01_000000_create_widgets_table', 'ran' => true ],
                [ 'migration' => '2024_01_02_000000_create_gadgets_table', 'ran' => true ],
            ],
            $this->migrator->status()
        );
    }

    public function testRollbackRevertsTheLastBatchInReverseOrder() {
        $this->migrator->migrate();

        $rolledBack = $this->migrator->rollback();

        $this->assertEquals(
            [ '2024_01_02_000000_create_gadgets_table', '2024_01_01_000000_create_widgets_table' ],
            $rolledBack
        );
        $this->assertTableDoesNotExist( 'widgets' );
        $this->assertTableDoesNotExist( 'gadgets' );
    }

    public function testStatusReflectsARollback() {
        $this->migrator->migrate();
        $this->migrator->rollback();

        $this->assertEquals(
            [
                [ 'migration' => '2024_01_01_000000_create_widgets_table', 'ran' => false ],
                [ 'migration' => '2024_01_02_000000_create_gadgets_table', 'ran' => false ],
            ],
            $this->migrator->status()
        );
    }

    private function assertTableExists( string $tableName ): void {
        $statement = $this->pdo->prepare( "SELECT name FROM sqlite_master WHERE type = 'table' AND name = :name" );
        $statement->bindParam( ':name', $tableName );
        $statement->execute();

        $this->assertNotFalse( $statement->fetch(), "Expected table '$tableName' to exist" );
    }

    private function assertTableDoesNotExist( string $tableName ): void {
        $statement = $this->pdo->prepare( "SELECT name FROM sqlite_master WHERE type = 'table' AND name = :name" );
        $statement->bindParam( ':name', $tableName );
        $statement->execute();

        $this->assertFalse( $statement->fetch(), "Expected table '$tableName' not to exist" );
    }

}
