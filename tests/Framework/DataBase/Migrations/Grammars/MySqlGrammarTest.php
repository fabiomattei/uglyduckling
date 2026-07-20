<?php

use Fabiom\UglyDuckling\Framework\DataBase\Migrations\Blueprint;
use Fabiom\UglyDuckling\Framework\DataBase\Migrations\Grammars\MySqlGrammar;

/**
 * Unit tests for MySqlGrammar's SQL text output. These run against the grammar
 * directly (no PDO connection) because engine/charset/collation and the new
 * column types are MySQL-specific and can't be exercised through the SQLite
 * connection the rest of the migrations test suite uses.
 */
class MySqlGrammarTest extends PHPUnit\Framework\TestCase {

    private MySqlGrammar $grammar;

    protected function setUp(): void {
        $this->grammar = new MySqlGrammar();
    }

    public function testIncrementsProducesAPlainIntegerPrimaryKeyNotBigint() {
        $blueprint = new Blueprint( 'widgets' );
        $blueprint->increments( 'wid_id' );

        [ $createStatement ] = $this->grammar->compileCreate( $blueprint );

        $this->assertStringContainsString( '`wid_id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY', $createStatement );
    }

    public function testIdProducesABigintPrimaryKey() {
        $blueprint = new Blueprint( 'widgets' );
        $blueprint->id();

        [ $createStatement ] = $this->grammar->compileCreate( $blueprint );

        $this->assertStringContainsString( '`id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY', $createStatement );
    }

    public function testCreateOmitsTableOptionsByDefault() {
        $blueprint = new Blueprint( 'widgets' );
        $blueprint->id();

        [ $createStatement ] = $this->grammar->compileCreate( $blueprint );

        $this->assertStringNotContainsString( 'ENGINE=', $createStatement );
        $this->assertStringNotContainsString( 'CHARSET=', $createStatement );
        $this->assertStringNotContainsString( 'COLLATE=', $createStatement );
    }

    public function testCreateAppliesEngineCharsetAndCollationWhenSet() {
        $blueprint = new Blueprint( 'widgets' );
        $blueprint->id();
        $blueprint->engine( 'InnoDB' );
        $blueprint->charset( 'utf8mb3' );
        $blueprint->collation( 'utf8mb3_bin' );

        [ $createStatement ] = $this->grammar->compileCreate( $blueprint );

        $this->assertStringEndsWith(
            ') ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin',
            $createStatement
        );
    }

    public function testCreateEmitsCompositePrimaryKeyAsATableConstraint() {
        $blueprint = new Blueprint( 'sessions' );
        $blueprint->string( 'session_string', 20 );
        $blueprint->string( 'token', 32 );
        $blueprint->primary( [ 'session_string', 'token' ] );

        [ $createStatement ] = $this->grammar->compileCreate( $blueprint );

        $this->assertStringContainsString( 'PRIMARY KEY (`session_string`, `token`)', $createStatement );
        // only one PRIMARY KEY clause - two inline ones would be invalid SQL
        $this->assertSame( 1, substr_count( $createStatement, 'PRIMARY KEY' ) );
    }

    public function testNewColumnTypesCompileToTheExpectedMySqlKeywords() {
        $blueprint = new Blueprint( 'gsr_reports' );
        $blueprint->time( 'occurred_at' );
        $blueprint->mediumText( 'payload' );
        $blueprint->longText( 'archive' );
        $blueprint->binary( 'attachment' );
        $blueprint->char( 'code', 8 );

        [ $createStatement ] = $this->grammar->compileCreate( $blueprint );

        $this->assertStringContainsString( '`occurred_at` TIME NOT NULL', $createStatement );
        $this->assertStringContainsString( '`payload` MEDIUMTEXT NOT NULL', $createStatement );
        $this->assertStringContainsString( '`archive` LONGTEXT NOT NULL', $createStatement );
        $this->assertStringContainsString( '`attachment` BLOB NOT NULL', $createStatement );
        $this->assertStringContainsString( '`code` CHAR(8) NOT NULL', $createStatement );
    }

}
