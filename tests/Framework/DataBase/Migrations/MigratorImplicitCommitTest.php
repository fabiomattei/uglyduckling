<?php

use Fabiom\UglyDuckling\Framework\DataBase\Migrations\MigrationRepository;
use Fabiom\UglyDuckling\Framework\DataBase\Migrations\Migrator;

/**
 * MySQL/InnoDB implicitly commits the current transaction on every DDL statement -
 * unlike SQLite (which the rest of this test suite runs against), where DDL is fully
 * transactional. This PDO subclass fakes that MySQL behavior on top of an in-memory
 * SQLite connection, so the implicit-commit code path in Migrator::runUp()/runDown()
 * can be exercised without a real MySQL server.
 */
class ImplicitCommitPdoStub extends PDO {

    private bool $transactionActive = false;

    public function beginTransaction(): bool {
        $this->transactionActive = true;
        return true;
    }

    public function inTransaction(): bool {
        return $this->transactionActive;
    }

    public function commit(): bool {
        if ( !$this->transactionActive ) {
            throw new PDOException( 'There is no active transaction' );
        }
        $this->transactionActive = false;
        return true;
    }

    public function rollBack(): bool {
        if ( !$this->transactionActive ) {
            throw new PDOException( 'There is no active transaction' );
        }
        $this->transactionActive = false;
        return true;
    }

    public function exec( string $statement ): int|false {
        $result = parent::exec( $statement );

        if ( preg_match( '/^\s*(CREATE|ALTER|DROP)\b/i', $statement ) === 1 ) {
            $this->transactionActive = false;
        }

        return $result;
    }

}

class MigratorImplicitCommitTest extends PHPUnit\Framework\TestCase {

    private function makeMigrator( string $fixturesSubdir ): Migrator {
        $pdo = new ImplicitCommitPdoStub( 'sqlite::memory:' );
        $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

        return new Migrator( $pdo, new MigrationRepository( $pdo ), __DIR__ . '/fixtures/' . $fixturesSubdir );
    }

    public function testMigrateSucceedsEvenThoughDdlImplicitlyCommitsMidMigration() {
        $migrator = $this->makeMigrator( 'implicit_commit_success' );

        $ran = $migrator->migrate();

        $this->assertEquals( [ '2024_01_01_000000_create_widgets_table' ], $ran );
        $this->assertEquals(
            [ [ 'migration' => '2024_01_01_000000_create_widgets_table', 'ran' => true ] ],
            $migrator->status()
        );
    }

    public function testAGenuineFailureAfterImplicitCommitStillSurfacesItsRealError() {
        $migrator = $this->makeMigrator( 'implicit_commit_failure' );

        try {
            $migrator->migrate();
            $this->fail( 'Expected a RuntimeException to propagate' );
        } catch ( RuntimeException $e ) {
            $this->assertSame( 'something went wrong after the DDL already ran', $e->getMessage() );
        }

        // The failing migration must not be recorded as having run, even though its
        // DDL (which can't be undone on MySQL once committed) already took effect.
        $ranNames = array_column(
            array_filter( $migrator->status(), fn( array $row ) => $row['ran'] ),
            'migration'
        );
        $this->assertNotContains( '2024_01_02_000000_create_then_fail', $ranNames );
    }

}
