<?php

use Fabiom\UglyDuckling\Framework\DataBase\Migrations\Blueprint;
use Fabiom\UglyDuckling\Framework\DataBase\Migrations\Schema;

/**
 * Integration tests for Schema/Blueprint against an in-memory SQLite database,
 * the same driver the test suite always runs migrations against.
 */
class SchemaBlueprintTest extends PHPUnit\Framework\TestCase {

    private PDO $pdo;

    protected function setUp(): void {
        $this->pdo = new PDO( 'sqlite::memory:' );
        $this->pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

        Schema::setConnection( $this->pdo );
    }

    public function testCreateBuildsAQueryableTableWithTheDeclaredColumns() {
        Schema::create( 'books', function ( Blueprint $table ) {
            $table->id();
            $table->string( 'title' );
            $table->text( 'description' )->nullable();
            $table->boolean( 'published' )->default( false );
            $table->decimal( 'price', 8, 2 )->nullable();
            $table->timestamps();
        } );

        $this->pdo->exec( "INSERT INTO books (title, published) VALUES ('Dune', 1)" );

        $row = $this->pdo->query( 'SELECT * FROM books' )->fetch( PDO::FETCH_ASSOC );

        $this->assertSame( '1', (string) $row['id'] );
        $this->assertSame( 'Dune', $row['title'] );
        $this->assertNull( $row['description'] );
        $this->assertSame( '1', (string) $row['published'] );
    }

    public function testTableAddsAndDropsColumns() {
        Schema::create( 'authors', function ( Blueprint $table ) {
            $table->id();
            $table->string( 'name' );
        } );

        Schema::table( 'authors', function ( Blueprint $table ) {
            $table->string( 'country' )->nullable();
        } );

        $this->pdo->exec( "INSERT INTO authors (name, country) VALUES ('Herbert', 'US')" );
        $row = $this->pdo->query( 'SELECT * FROM authors' )->fetch( PDO::FETCH_ASSOC );
        $this->assertSame( 'US', $row['country'] );

        Schema::table( 'authors', function ( Blueprint $table ) {
            $table->dropColumn( 'country' );
        } );

        $columns = $this->pdo->query( 'PRAGMA table_info(authors)' )->fetchAll( PDO::FETCH_ASSOC );
        $this->assertNotContains( 'country', array_column( $columns, 'name' ) );
    }

    public function testDropIfExistsDoesNotFailWhenTableIsMissing() {
        Schema::dropIfExists( 'missing_table' );

        $this->assertFalse( Schema::hasTable( 'missing_table' ) );
    }

    public function testDropRemovesAnExistingTable() {
        Schema::create( 'temp_table', function ( Blueprint $table ) {
            $table->id();
        } );
        $this->assertTrue( Schema::hasTable( 'temp_table' ) );

        Schema::drop( 'temp_table' );

        $this->assertFalse( Schema::hasTable( 'temp_table' ) );
    }

    public function testUniqueIndexIsEnforced() {
        Schema::create( 'tags', function ( Blueprint $table ) {
            $table->id();
            $table->string( 'slug' );
            $table->unique( 'slug' );
        } );

        $this->pdo->exec( "INSERT INTO tags (slug) VALUES ('sci-fi')" );

        $this->expectException( PDOException::class );
        $this->pdo->exec( "INSERT INTO tags (slug) VALUES ('sci-fi')" );
    }

}
