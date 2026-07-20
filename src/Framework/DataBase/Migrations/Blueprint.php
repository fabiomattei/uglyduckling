<?php

namespace Fabiom\UglyDuckling\Framework\DataBase\Migrations;

/**
 * Fluent column/index definition passed into Schema::create()/Schema::table() closures.
 */
class Blueprint {

    private string $table;
    /** @var ColumnDefinition[] */
    private array $columns = [];
    private array $indexes = [];
    private array $droppedColumns = [];
    private ?array $primaryKey = null;
    private ?string $engine = null;
    private ?string $charset = null;
    private ?string $collation = null;

    public function __construct( string $table ) {
        $this->table = $table;
    }

    public function id( string $name = 'id' ): ColumnDefinition {
        return $this->addColumn( $name, 'bigInteger' )->unsigned()->markAsAutoIncrementPrimaryKey();
    }

    public function increments( string $name ): ColumnDefinition {
        return $this->addColumn( $name, 'integer' )->markAsAutoIncrementPrimaryKey();
    }

    public function string( string $name, int $length = 255 ): ColumnDefinition {
        return $this->addColumn( $name, 'string', [ $length ] );
    }

    public function text( string $name ): ColumnDefinition {
        return $this->addColumn( $name, 'text' );
    }

    public function integer( string $name ): ColumnDefinition {
        return $this->addColumn( $name, 'integer' );
    }

    public function bigInteger( string $name ): ColumnDefinition {
        return $this->addColumn( $name, 'bigInteger' );
    }

    public function unsignedBigInteger( string $name ): ColumnDefinition {
        return $this->addColumn( $name, 'bigInteger' )->unsigned();
    }

    /**
     * Unsigned big integer column for a foreign key value. Chain ->constrained($table)
     * or ->references($column)->on($table) to also create the FOREIGN KEY constraint.
     */
    public function foreignId( string $name ): ColumnDefinition {
        return $this->unsignedBigInteger( $name );
    }

    /**
     * CHAR(36) column for a UUID value, e.g. as a primary key: ->uuid('id')->primary().
     * Nothing generates the UUID value itself - the application does that in PHP before
     * inserting, the same way Laravel's HasUuids trait does; there is no portable
     * DB-side UUID function shared by MySQL and SQLite.
     */
    public function uuid( string $name ): ColumnDefinition {
        return $this->addColumn( $name, 'uuid' );
    }

    /**
     * CHAR(36) column for a foreign key value that references a uuid() primary key.
     * Chain ->constrained($table) or ->references($column)->on($table) as with foreignId().
     */
    public function foreignUuid( string $name ): ColumnDefinition {
        return $this->uuid( $name );
    }

    public function boolean( string $name ): ColumnDefinition {
        return $this->addColumn( $name, 'boolean' );
    }

    public function decimal( string $name, int $precision = 8, int $scale = 2 ): ColumnDefinition {
        return $this->addColumn( $name, 'decimal', [ $precision, $scale ] );
    }

    public function date( string $name ): ColumnDefinition {
        return $this->addColumn( $name, 'date' );
    }

    public function dateTime( string $name ): ColumnDefinition {
        return $this->addColumn( $name, 'dateTime' );
    }

    /**
     * A time-of-day value with no date part (e.g. a scheduled arrival time).
     * Distinct from dateTime() - there is no portable "just a time" type shared by
     * every dialect, but both MySQL's TIME and SQLite's (untyped/NUMERIC affinity)
     * column accept the same 'HH:MM:SS' string, so a literal TIME keyword works for both.
     */
    public function time( string $name ): ColumnDefinition {
        return $this->addColumn( $name, 'time' );
    }

    public function timestamp( string $name ): ColumnDefinition {
        return $this->addColumn( $name, 'timestamp' );
    }

    /**
     * Larger text storage than text() (MySQL's TEXT truncates at 64KB; MEDIUMTEXT
     * doesn't). SQLite has no size-tiered text types, so this is a MySQL-motivated
     * escape hatch - on SQLite it behaves exactly like text().
     */
    public function mediumText( string $name ): ColumnDefinition {
        return $this->addColumn( $name, 'mediumText' );
    }

    /**
     * Raw binary storage (e.g. an uploaded file's bytes).
     */
    public function binary( string $name ): ColumnDefinition {
        return $this->addColumn( $name, 'blob' );
    }

    /**
     * Fixed-length string, unlike string()/VARCHAR. Mainly useful for values with a
     * known constant length (hashes, fixed tokens) where padding behavior doesn't matter.
     */
    public function char( string $name, int $length = 255 ): ColumnDefinition {
        return $this->addColumn( $name, 'char', [ $length ] );
    }

    /**
     * Adds nullable created_at/updated_at datetime columns, matching the field names
     * BasicDao::insert() already writes (DB_TABLE_CREATED_FLIED_NAME / DB_TABLE_UPDATED_FIELD_NAME).
     */
    public function timestamps(): void {
        $this->dateTime( 'created_at' )->nullable();
        $this->dateTime( 'updated_at' )->nullable();
    }

    /**
     * @param string|string[] $columns
     */
    public function index( $columns, ?string $name = null ): void {
        $columns = (array) $columns;
        $this->indexes[] = [
            'columns' => $columns,
            'unique' => false,
            'name' => $name ?? $this->table . '_' . implode( '_', $columns ) . '_index',
        ];
    }

    /**
     * @param string|string[] $columns
     */
    public function unique( $columns, ?string $name = null ): void {
        $columns = (array) $columns;
        $this->indexes[] = [
            'columns' => $columns,
            'unique' => true,
            'name' => $name ?? $this->table . '_' . implode( '_', $columns ) . '_unique',
        ];
    }

    /**
     * @param string|string[] $columns
     */
    public function dropColumn( $columns ): void {
        $this->droppedColumns = array_merge( $this->droppedColumns, (array) $columns );
    }

    /**
     * Table-level primary key spanning one or more columns. Use this instead of
     * ColumnDefinition::primary() whenever the key has no single-column id - e.g. a
     * composite key - since a MySQL CREATE TABLE only allows one PRIMARY KEY clause
     * and per-column ->primary() on two columns would emit two, which is invalid SQL.
     *
     * @param string|string[] $columns
     */
    public function primary( $columns, ?string $name = null ): void {
        $this->primaryKey = [
            'columns' => (array) $columns,
            'name' => $name,
        ];
    }

    public function getPrimaryKey(): ?array {
        return $this->primaryKey;
    }

    /**
     * Storage engine for the CREATE TABLE statement (MySQL only - SQLite ignores it).
     * Unset by default so existing migrations keep generating exactly the SQL they did
     * before this existed.
     */
    public function engine( string $engine ): void {
        $this->engine = $engine;
    }

    public function getEngine(): ?string {
        return $this->engine;
    }

    /**
     * Default charset for the CREATE TABLE statement (MySQL only - SQLite ignores it).
     */
    public function charset( string $charset ): void {
        $this->charset = $charset;
    }

    public function getCharset(): ?string {
        return $this->charset;
    }

    /**
     * Default collation for the CREATE TABLE statement (MySQL only - SQLite ignores it).
     */
    public function collation( string $collation ): void {
        $this->collation = $collation;
    }

    public function getCollation(): ?string {
        return $this->collation;
    }

    public function getTable(): string {
        return $this->table;
    }

    /**
     * @return ColumnDefinition[]
     */
    public function getColumns(): array {
        return $this->columns;
    }

    public function getIndexes(): array {
        return $this->indexes;
    }

    /**
     * @return string[]
     */
    public function getDroppedColumns(): array {
        return $this->droppedColumns;
    }

    private function addColumn( string $name, string $type, array $args = [] ): ColumnDefinition {
        $column = new ColumnDefinition( $name, $type, $args );
        $this->columns[] = $column;
        return $column;
    }

}
