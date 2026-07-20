<?php

use Fabiom\UglyDuckling\Framework\DataBase\Seeders\SeederRunner;

/**
 *  Testing the SeederRunner class against real seeder fixture files and an in-memory SQLite database
 */
class SeederRunnerTest extends PHPUnit\Framework\TestCase {

    private PDO $pdo;
    private SeederRunner $seederRunner;

    protected function setUp(): void {
        $this->pdo = new PDO( 'sqlite::memory:' );
        $this->pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        $this->pdo->exec( 'CREATE TABLE widgets (id INTEGER PRIMARY KEY, name VARCHAR(255))' );
        $this->pdo->exec( 'CREATE TABLE gadgets (id INTEGER PRIMARY KEY, name VARCHAR(255))' );

        $this->seederRunner = new SeederRunner( $this->pdo, __DIR__ . '/fixtures/seeders' );
    }

    public function testRunRunsEverySeederInFilenameOrder() {
        $ran = $this->seederRunner->run();

        $this->assertEquals(
            [ '2024_01_01_000000_seed_widgets', '2024_01_02_000000_seed_gadgets' ],
            $ran
        );
        $this->assertEquals( [ 'bolt' ], $this->pdo->query( 'SELECT name FROM widgets' )->fetchAll( PDO::FETCH_COLUMN ) );
        $this->assertEquals( [ 'cog' ], $this->pdo->query( 'SELECT name FROM gadgets' )->fetchAll( PDO::FETCH_COLUMN ) );
    }

    public function testRunIsNotTrackedAndReSeedsOnEachCall() {
        $this->seederRunner->run();
        $this->seederRunner->run();

        $this->assertEquals(
            [ 'bolt', 'bolt' ],
            $this->pdo->query( 'SELECT name FROM widgets' )->fetchAll( PDO::FETCH_COLUMN )
        );
    }

    public function testRunOneRunsOnlyTheNamedSeeder() {
        $this->seederRunner->runOne( '2024_01_01_000000_seed_widgets' );

        $this->assertEquals( [ 'bolt' ], $this->pdo->query( 'SELECT name FROM widgets' )->fetchAll( PDO::FETCH_COLUMN ) );
        $this->assertEquals( [], $this->pdo->query( 'SELECT name FROM gadgets' )->fetchAll( PDO::FETCH_COLUMN ) );
    }

    public function testRunOneThrowsWhenSeederFileDoesNotExist() {
        $this->expectException( RuntimeException::class );

        $this->seederRunner->runOne( 'does_not_exist' );
    }

    /**
     * @dataProvider classNameForSeederProvider
     */
    public function testClassNameForSeederStripsTimestampAndConvertsToStudlyCase( string $seederName, string $expectedClassName ) {
        $this->assertEquals( $expectedClassName, SeederRunner::classNameForSeeder( $seederName ) );
    }

    public function classNameForSeederProvider(): array {
        return [
            [ '2026_07_20_180602_seed_authors', 'SeedAuthors' ],
            [ 'seed_authors', 'SeedAuthors' ],
            [ '2024_01_01_000000_seed_widgets', 'SeedWidgets' ],
            [ 'seed-kebab-case', 'SeedKebabCase' ],
        ];
    }

    public function testFailingSeederRollsBackItsOwnTransaction() {
        $tempDir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid( 'ud_seeder_test_', true );
        mkdir( $tempDir );
        $failingSeederFile = $tempDir . DIRECTORY_SEPARATOR . 'failing_seeder.php';
        file_put_contents( $failingSeederFile, <<<'PHP'
<?php
namespace Database\Seeders;
use Fabiom\UglyDuckling\Framework\DataBase\Seeders\Seeder;
use PDO;
class FailingSeeder extends Seeder {
    public function run( PDO $pdo ): void {
        $pdo->exec( "INSERT INTO widgets (name) VALUES ('bolt')" );
        throw new \RuntimeException( 'boom' );
    }
};
PHP
        );

        $runner = new SeederRunner( $this->pdo, $tempDir );

        try {
            $this->expectException( RuntimeException::class );
            $runner->runOne( 'failing_seeder' );
        } finally {
            unlink( $failingSeederFile );
            rmdir( $tempDir );
        }
    }

}
