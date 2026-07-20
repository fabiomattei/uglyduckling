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
        $lines = array_map(
            fn( ColumnDefinition $column ) => $this->compileColumn( $column ),
            $blueprint->getColumns()
        );

        foreach ( $blueprint->getColumns() as $column ) {
            if ( $column->hasForeignKey() ) {
                $lines[] = $this->compileForeignKeyClause( $column, $this->foreignKeyConstraintName( $blueprint, $column ) );
            }
        }

        if ( $blueprint->getPrimaryKey() !== null ) {
            $lines[] = $this->compilePrimaryKeyClause( $blueprint->getPrimaryKey() );
        }

        $statements = [
            'CREATE TABLE ' . $this->quoteIdentifier( $blueprint->getTable() ) . ' (' . implode( ', ', $lines ) . ')'
                . $this->compileTableOptions( $blueprint ),
        ];

        return array_merge( $statements, $this->compileIndexes( $blueprint ) );
    }

    /**
     * Table-level PRIMARY KEY clause for Blueprint::primary() - a composite key, or any
     * key not tied to a single auto-increment/uuid column declared via ColumnDefinition::primary().
     */
    protected function compilePrimaryKeyClause( array $primaryKey ): string {
        $columns = implode( ', ', array_map( fn( string $c ) => $this->quoteIdentifier( $c ), $primaryKey['columns'] ) );

        return 'PRIMARY KEY (' . $columns . ')';
    }

    /**
     * Trailing CREATE TABLE options (engine/charset/collation). Empty by default so
     * dialects/migrations that don't set them keep generating identical SQL to before
     * these existed; MySqlGrammar overrides this to emit whichever of engine()/charset()/
     * collation() the migration actually called on the Blueprint.
     */
    protected function compileTableOptions( Blueprint $blueprint ): string {
        return '';
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
            $statements = array_merge( $statements, $this->compileAddColumnStatements( $blueprint, $column ) );
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

        if ( $column->isPrimary() ) {
            $sql .= ' PRIMARY KEY';
        }

        if ( $column->isUnique() ) {
            $sql .= ' UNIQUE';
        }

        return $sql;
    }

    /**
     * One or more ALTER TABLE statements that add this single column, wiring up its
     * foreign key (if any). Split out because MySQL and SQLite handle a foreign key
     * added after table creation differently - see MySqlGrammar/SQLiteGrammar.
     *
     * @return string[]
     */
    protected function compileAddColumnStatements( Blueprint $blueprint, ColumnDefinition $column ): array {
        return [
            'ALTER TABLE ' . $this->quoteIdentifier( $blueprint->getTable() )
                . ' ADD COLUMN ' . $this->compileColumn( $column ),
        ];
    }

    protected function compileForeignKeyClause( ColumnDefinition $column, ?string $constraintName = null ): string {
        $sql = $constraintName !== null ? 'CONSTRAINT ' . $this->quoteIdentifier( $constraintName ) . ' ' : '';
        $sql .= 'FOREIGN KEY (' . $this->quoteIdentifier( $column->getName() ) . ') REFERENCES '
            . $this->quoteIdentifier( $column->getReferencesTable() )
            . ' (' . $this->quoteIdentifier( $column->getReferencesColumn() ) . ')';

        return $sql . $this->compileReferentialActions( $column );
    }

    protected function compileReferentialActions( ColumnDefinition $column ): string {
        $sql = '';

        if ( $column->getOnDelete() !== null ) {
            $sql .= ' ON DELETE ' . $column->getOnDelete();
        }
        if ( $column->getOnUpdate() !== null ) {
            $sql .= ' ON UPDATE ' . $column->getOnUpdate();
        }

        return $sql;
    }

    protected function foreignKeyConstraintName( Blueprint $blueprint, ColumnDefinition $column ): string {
        return $blueprint->getTable() . '_' . $column->getName() . '_foreign';
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
            case 'uuid':
                return 'CHAR(36)';
            case 'decimal':
                return 'DECIMAL(' . ( $args[0] ?? 8 ) . ', ' . ( $args[1] ?? 2 ) . ')';
            case 'date':
                return 'DATE';
            case 'dateTime':
                return 'DATETIME';
            case 'timestamp':
                return 'TIMESTAMP';
            case 'time':
                return 'TIME';
            case 'mediumText':
                return 'MEDIUMTEXT';
            case 'longText':
                return 'LONGTEXT';
            case 'blob':
                return 'BLOB';
            case 'char':
                return 'CHAR(' . ( $args[0] ?? 255 ) . ')';
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
