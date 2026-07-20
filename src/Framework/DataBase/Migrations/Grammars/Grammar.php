<?php

namespace Fabiom\UglyDuckling\Framework\DataBase\Migrations\Grammars;

use Fabiom\UglyDuckling\Framework\DataBase\Migrations\Blueprint;
use Fabiom\UglyDuckling\Framework\DataBase\Migrations\ColumnDefinition;
use RuntimeException;

/**
 * Compiles a Blueprint into the DDL statements for one SQL dialect.
 * Column-type mapping is shared here because it is identical across dialects;
 * only identifier quoting, the auto-increment primary key column, and the
 * "does this table exist" query differ per dialect (see MySqlGrammar/SQLiteGrammar).
 */
abstract class Grammar {

    /**
     * @return string[] statements to run, in order
     */
    public function compileCreate( Blueprint $blueprint ): array {
        $columnLines = array_map(
            fn( ColumnDefinition $column ) => $this->compileColumn( $column ),
            $blueprint->getColumns()
        );

        $statements = [
            'CREATE TABLE ' . $this->quoteIdentifier( $blueprint->getTable() ) . ' (' . implode( ', ', $columnLines ) . ')',
        ];

        return array_merge( $statements, $this->compileIndexes( $blueprint ) );
    }

    /**
     * One ADD COLUMN / DROP COLUMN statement per column, since SQLite (unlike MySQL)
     * cannot batch multiple column changes into a single ALTER TABLE statement.
     *
     * @return string[] statements to run, in order
     */
    public function compileAlter( Blueprint $blueprint ): array {
        $statements = [];

        foreach ( $blueprint->getColumns() as $column ) {
            $statements[] = 'ALTER TABLE ' . $this->quoteIdentifier( $blueprint->getTable() )
                . ' ADD COLUMN ' . $this->compileColumn( $column );
        }

        foreach ( $blueprint->getDroppedColumns() as $columnName ) {
            $statements[] = 'ALTER TABLE ' . $this->quoteIdentifier( $blueprint->getTable() )
                . ' DROP COLUMN ' . $this->quoteIdentifier( $columnName );
        }

        return array_merge( $statements, $this->compileIndexes( $blueprint ) );
    }

    public function compileDrop( string $table ): string {
        return 'DROP TABLE ' . $this->quoteIdentifier( $table );
    }

    public function compileDropIfExists( string $table ): string {
        return 'DROP TABLE IF EXISTS ' . $this->quoteIdentifier( $table );
    }

    /**
     * @return array{0: string, 1: array<int, string>} [sql, bindings]
     */
    abstract public function compileHasTable( string $table ): array;

    /**
     * Query returning the name of every table in the current database, used by
     * Migrator::fresh() to drop everything before re-running migrations from scratch.
     */
    abstract public function compileTableNames(): string;

    public function quoteIdentifier( string $name ): string {
        return '"' . str_replace( '"', '""', $name ) . '"';
    }

    /**
     * Full definition of an auto-incrementing primary key column. Dialect-specific:
     * SQLite requires the literal type "INTEGER" for its AUTOINCREMENT rowid alias,
     * while MySQL uses "BIGINT UNSIGNED ... AUTO_INCREMENT".
     */
    abstract protected function autoIncrementPrimaryKeyColumn( ColumnDefinition $column ): string;

    protected function compileColumn( ColumnDefinition $column ): string {
        if ( $column->isAutoIncrement() && $column->isPrimary() ) {
            return $this->autoIncrementPrimaryKeyColumn( $column );
        }

        $sql = $this->quoteIdentifier( $column->getName() ) . ' ' . $this->typeKeyword( $column );

        if ( $column->isUnsigned() ) {
            $sql .= ' UNSIGNED';
        }

        $sql .= $column->isNullable() ? ' NULL' : ' NOT NULL';

        if ( $column->hasDefault() ) {
            $sql .= ' DEFAULT ' . $this->compileDefaultValue( $column->getDefault() );
        }

        if ( $column->isUnique() ) {
            $sql .= ' UNIQUE';
        }

        return $sql;
    }

    protected function typeKeyword( ColumnDefinition $column ): string {
        $args = $column->getArgs();

        switch ( $column->getType() ) {
            case 'string':
                return 'VARCHAR(' . ( $args[0] ?? 255 ) . ')';
            case 'text':
                return 'TEXT';
            case 'integer':
                return 'INTEGER';
            case 'bigInteger':
                return 'BIGINT';
            case 'boolean':
                return 'BOOLEAN';
            case 'decimal':
                return 'DECIMAL(' . ( $args[0] ?? 8 ) . ', ' . ( $args[1] ?? 2 ) . ')';
            case 'date':
                return 'DATE';
            case 'dateTime':
                return 'DATETIME';
            case 'timestamp':
                return 'TIMESTAMP';
            default:
                throw new RuntimeException( 'Unsupported column type: ' . $column->getType() );
        }
    }

    protected function compileDefaultValue( $value ): string {
        if ( is_bool( $value ) ) {
            return $value ? '1' : '0';
        }
        if ( is_int( $value ) || is_float( $value ) ) {
            return (string) $value;
        }
        if ( $value === null ) {
            return 'NULL';
        }

        return "'" . str_replace( "'", "''", (string) $value ) . "'";
    }

    /**
     * @return string[]
     */
    protected function compileIndexes( Blueprint $blueprint ): array {
        $statements = [];

        foreach ( $blueprint->getIndexes() as $index ) {
            $columns = implode( ', ', array_map( fn( string $c ) => $this->quoteIdentifier( $c ), $index['columns'] ) );
            $keyword = $index['unique'] ? 'CREATE UNIQUE INDEX ' : 'CREATE INDEX ';

            $statements[] = $keyword . $this->quoteIdentifier( $index['name'] )
                . ' ON ' . $this->quoteIdentifier( $blueprint->getTable() ) . ' (' . $columns . ')';
        }

        return $statements;
    }

}
