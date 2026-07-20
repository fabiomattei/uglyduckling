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

    public function testForeignKeyDeclaredWithConstrainedIsEnforcedOnCreate() {
        Schema::create( 'authors', function ( Blueprint $table ) {
            $table->id();
        } );
        Schema::create( 'books', function ( Blueprint $table ) {
            $table->id();
            $table->foreignId( 'author_id' )->constrained( 'authors' );
        } );

        $this->pdo->exec( 'INSERT INTO authors (id) VALUES (1)' );
        $this->pdo->exec( 'INSERT INTO books (author_id) VALUES (1)' );

        $this->expectException( PDOException::class );
        $this->pdo->exec( 'INSERT INTO books (author_id) VALUES (999)' );
    }

    public function testForeignKeyDeclaredWithReferencesOnIsEnforcedOnCreate() {
        Schema::create( 'authors', function ( Blueprint $table ) {
            $table->id();
        } );
        Schema::create( 'books', function ( Blueprint $table ) {
            $table->id();
            $table->foreignId( 'author_id' )->references( 'id' )->on( 'authors' );
        } );

        $this->expectException( PDOException::class );
        $this->pdo->exec( 'INSERT INTO books (author_id) VALUES (999)' );
    }

    public function testForeignKeyAddedThroughTableIsEnforced() {
        Schema::create( 'authors', function ( Blueprint $table ) {
            $table->id();
        } );
        Schema::create( 'books', function ( Blueprint $table ) {
            $table->id();
        } );

        Schema::table( 'books', function ( Blueprint $table ) {
            $table->foreignId( 'author_id' )->nullable()->constrained( 'authors' );
        } );

        $this->pdo->exec( 'INSERT INTO authors (id) VALUES (1)' );
        $this->pdo->exec( 'INSERT INTO books (author_id) VALUES (1)' );

        $this->expectException( PDOException::class );
        $this->pdo->exec( 'INSERT INTO books (author_id) VALUES (999)' );
    }

    public function testOnDeleteCascadeDeletesDependentRows() {
        Schema::create( 'authors', function ( Blueprint $table ) {
            $table->id();
        } );
        Schema::create( 'books', function ( Blueprint $table ) {
            $table->id();
            $table->foreignId( 'author_id' )->constrained( 'authors' )->onDelete( 'cascade' );
        } );

        $this->pdo->exec( 'INSERT INTO authors (id) VALUES (1)' );
        $this->pdo->exec( 'INSERT INTO books (author_id) VALUES (1)' );

        $this->pdo->exec( 'DELETE FROM authors WHERE id = 1' );

        $this->assertEquals( [], $this->pdo->query( 'SELECT * FROM books' )->fetchAll() );
    }

    public function testOnDeleteRejectsAnUnsupportedAction() {
        $this->expectException( InvalidArgumentException::class );

        ( new Blueprint( 'books' ) )->foreignId( 'author_id' )->onDelete( 'purge' );
    }

    public function testUuidPrimaryKeyIsQueryableAndUnique() {
        Schema::create( 'authors', function ( Blueprint $table ) {
            $table->uuid( 'id' )->primary();
            $table->string( 'name' );
        } );

        $uuid = '4c1b9a3e-1111-4b6e-9a2b-000000000001';
        $this->pdo->exec( "INSERT INTO authors (id, name) VALUES ('$uuid', 'Herbert')" );

        $row = $this->pdo->query( 'SELECT * FROM authors' )->fetch( PDO::FETCH_ASSOC );
        $this->assertSame( $uuid, $row['id'] );

        $this->expectException( PDOException::class );
        $this->pdo->exec( "INSERT INTO authors (id, name) VALUES ('$uuid', 'Duplicate')" );
    }

    public function testTimeMediumTextBinaryAndCharColumnsRoundTrip() {
        Schema::create( 'shifts', function ( Blueprint $table ) {
            $table->id();
            $table->time( 'starts_at' );
            $table->mediumText( 'notes' )->nullable();
            $table->binary( 'badge' )->nullable();
            $table->char( 'code', 8 );
        } );

        $this->pdo->exec(
            "INSERT INTO shifts (starts_at, notes, badge, code) VALUES ('08:30:00', 'long notes', X'DEAD', 'AB12CD34')"
        );

        $row = $this->pdo->query( 'SELECT * FROM shifts' )->fetch( PDO::FETCH_ASSOC );
        $this->assertSame( '08:30:00', $row['starts_at'] );
        $this->assertSame( 'long notes', $row['notes'] );
        $this->assertSame( 'AB12CD34', $row['code'] );
    }

    public function testCompositePrimaryKeyIsEnforced() {
        Schema::create( 'sessions', function ( Blueprint $table ) {
            $table->string( 'session_string', 20 );
            $table->string( 'token', 32 );
            $table->primary( [ 'session_string', 'token' ] );
        } );

        $this->pdo->exec( "INSERT INTO sessions (session_string, token) VALUES ('abc', 'xyz')" );

        $this->expectException( PDOException::class );
        $this->pdo->exec( "INSERT INTO sessions (session_string, token) VALUES ('abc', 'xyz')" );
    }

    public function testForeignUuidReferencesAUuidPrimaryKey() {
        Schema::create( 'authors', function ( Blueprint $table ) {
            $table->uuid( 'id' )->primary();
        } );
        Schema::create( 'books', function ( Blueprint $table ) {
            $table->id();
            $table->foreignUuid( 'author_id' )->constrained( 'authors' );
        } );

        $uuid = '4c1b9a3e-1111-4b6e-9a2b-000000000002';
        $this->pdo->exec( "INSERT INTO authors (id) VALUES ('$uuid')" );
        $this->pdo->exec( "INSERT INTO books (author_id) VALUES ('$uuid')" );

        $this->expectException( PDOException::class );
        $this->pdo->exec( "INSERT INTO books (author_id) VALUES ('not-a-real-author')" );
    }

}
