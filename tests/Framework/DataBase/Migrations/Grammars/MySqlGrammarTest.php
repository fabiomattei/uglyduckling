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

    public function testColumnOmitsCharsetCollationAndCommentByDefault() {
        $blueprint = new Blueprint( 'widgets' );
        $blueprint->string( 'name' );

        [ $createStatement ] = $this->grammar->compileCreate( $blueprint );

        $this->assertStringContainsString( '`name` VARCHAR(255) NOT NULL', $createStatement );
        $this->assertStringNotContainsString( 'CHARACTER SET', $createStatement );
        $this->assertStringNotContainsString( 'COLLATE', $createStatement );
        $this->assertStringNotContainsString( 'COMMENT', $createStatement );
    }

    public function testColumnAppliesCharsetCollationAndCommentWhenSet() {
        $blueprint = new Blueprint( 'crm_contacts' );
        $blueprint->string( 'cn_coid', 36 )->collation( 'utf8mb4_bin' )->comment( 'link a company' );
        $blueprint->string( 'cn_name', 255 )->charset( 'utf8mb3' )->collation( 'utf8mb3_bin' )->nullable();

        [ $createStatement ] = $this->grammar->compileCreate( $blueprint );

        $this->assertStringContainsString(
            "`cn_coid` VARCHAR(36) COLLATE utf8mb4_bin NOT NULL COMMENT 'link a company'",
            $createStatement
        );
        $this->assertStringContainsString(
            '`cn_name` VARCHAR(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NULL',
            $createStatement
        );
    }

    public function testColumnCommentEscapesSingleQuotes() {
        $blueprint = new Blueprint( 'widgets' );
        $blueprint->string( 'note' )->comment( "it's here" );

        [ $createStatement ] = $this->grammar->compileCreate( $blueprint );

        $this->assertStringContainsString( "COMMENT 'it''s here'", $createStatement );
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
