<?php

namespace Fabiom\UglyDuckling\Framework\DataBase\Migrations\Grammars;

use Fabiom\UglyDuckling\Framework\DataBase\Migrations\Blueprint;
use Fabiom\UglyDuckling\Framework\DataBase\Migrations\ColumnDefinition;

class MySqlGrammar extends Grammar {

    public function quoteIdentifier( string $name ): string {
        return '`' . str_replace( '`', '``', $name ) . '`';
    }

    public function compileHasTable( string $table ): array {
        return [
            'SELECT 1 FROM information_schema.tables WHERE table_schema = DATABASE() AND table_name = ?',
            [ $table ],
        ];
    }

    public function compileTableNames(): string {
        return 'SELECT table_name FROM information_schema.tables WHERE table_schema = DATABASE()';
    }

    /**
     * Respects whether the column was declared via id() ('bigInteger') or increments()
     * ('integer') - autoIncrementPrimaryKeyColumn() used to hardcode BIGINT regardless,
     * silently widening every increments() primary key to BIGINT on MySQL even though
     * increments() is documented (and behaves, on SQLite) as producing a plain INTEGER.
     *
     * Also respects ColumnDefinition::isUnsigned() rather than hardcoding UNSIGNED - both
     * id() and increments() set it by default, but this used to ignore an explicit
     * ->unsigned( false ) override for the rarer case of a plain (signed) auto-increment
     * column.
     */
    protected function autoIncrementPrimaryKeyColumn( ColumnDefinition $column ): string {
        $type = $column->getType() === 'integer' ? 'INT' : 'BIGINT';
        $unsigned = $column->isUnsigned() ? ' UNSIGNED' : '';

        return $this->quoteIdentifier( $column->getName() ) . ' ' . $type . $unsigned . ' NOT NULL AUTO_INCREMENT PRIMARY KEY';
    }

    /**
     * MySQL/InnoDB ignores a REFERENCES clause written inline on a column definition -
     * it only creates and enforces a foreign key from a table-level FOREIGN KEY clause,
     * added here as a second statement since ALTER TABLE ADD COLUMN can't carry one.
     *
     * @return string[]
     */
    protected function compileAddColumnStatements( Blueprint $blueprint, ColumnDefinition $column ): array {
        $statements = parent::compileAddColumnStatements( $blueprint, $column );

        if ( $column->hasForeignKey() ) {
            $statements[] = 'ALTER TABLE ' . $this->quoteIdentifier( $blueprint->getTable() )
                . ' ADD ' . $this->compileForeignKeyClause( $column, $this->foreignKeyConstraintName( $blueprint, $column ) );
        }

        return $statements;
    }

    /**
     * Emits CHARACTER SET/COLLATE only for whichever of ColumnDefinition::charset()/
     * collation() the migration actually called - a column that sets neither generates
     * the same SQL as before these existed.
     */
    protected function compileColumnCharsetAndCollation( ColumnDefinition $column ): string {
        $sql = '';

        if ( $column->getCharset() !== null ) {
            $sql .= ' CHARACTER SET ' . $column->getCharset();
        }
        if ( $column->getCollation() !== null ) {
            $sql .= ' COLLATE ' . $column->getCollation();
        }

        return $sql;
    }

    /**
     * Emits a trailing COMMENT clause when the migration called ColumnDefinition::comment().
     */
    protected function compileColumnComment( ColumnDefinition $column ): string {
        if ( $column->getComment() === null ) {
            return '';
        }

        return ' COMMENT ' . $this->compileDefaultValue( $column->getComment() );
    }

    /**
     * Emits ON UPDATE CURRENT_TIMESTAMP when the migration called
     * ColumnDefinition::useCurrentOnUpdate().
     */
    protected function compileColumnOnUpdate( ColumnDefinition $column ): string {
        return $column->usesCurrentOnUpdate() ? ' ON UPDATE CURRENT_TIMESTAMP' : '';
    }

    /**
     * Only emits ENGINE/CHARSET/COLLATE clauses the migration explicitly set via
     * Blueprint::engine()/charset()/collation() - a migration that calls none of them
     * generates the exact same CREATE TABLE statement as before these options existed.
     */
    protected function compileTableOptions( Blueprint $blueprint ): string {
        $sql = '';

        if ( $blueprint->getEngine() !== null ) {
            $sql .= ' ENGINE=' . $blueprint->getEngine();
        }
        if ( $blueprint->getCharset() !== null ) {
            $sql .= ' DEFAULT CHARSET=' . $blueprint->getCharset();
        }
        if ( $blueprint->getCollation() !== null ) {
            $sql .= ' COLLATE=' . $blueprint->getCollation();
        }

        return $sql;
    }

}
