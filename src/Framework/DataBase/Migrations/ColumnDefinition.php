<?php

namespace Fabiom\UglyDuckling\Framework\DataBase\Migrations;

/**
 * A single column inside a Blueprint, with Laravel-style fluent modifiers.
 * Blueprint's column methods (string(), integer(), ...) return an instance of this.
 */
class ColumnDefinition {

    private string $name;
    private string $type;
    private array $args;
    private bool $nullable = false;
    private bool $unsigned = false;
    private bool $autoIncrement = false;
    private bool $primary = false;
    private bool $unique = false;
    private bool $hasDefault = false;
    private $default = null;
    private ?string $referencesTable = null;
    private ?string $referencesColumn = null;
    private ?string $onDeleteAction = null;
    private ?string $onUpdateAction = null;

    public function __construct( string $name, string $type, array $args = [] ) {
        $this->name = $name;
        $this->type = $type;
        $this->args = $args;
    }

    public function nullable( bool $value = true ): self {
        $this->nullable = $value;
        return $this;
    }

    public function default( $value ): self {
        $this->hasDefault = true;
        $this->default = $value;
        return $this;
    }

    public function unsigned( bool $value = true ): self {
        $this->unsigned = $value;
        return $this;
    }

    public function unique( bool $value = true ): self {
        $this->unique = $value;
        return $this;
    }

    /**
     * Marks this column the table's primary key, without auto-increment - for an
     * auto-incrementing primary key use Blueprint::id()/increments() instead.
     */
    public function primary( bool $value = true ): self {
        $this->primary = $value;
        return $this;
    }

    /**
     * Set by Blueprint::id()/increments(); not part of the public fluent API.
     */
    public function markAsAutoIncrementPrimaryKey(): self {
        $this->autoIncrement = true;
        $this->primary = true;
        return $this;
    }

    public function references( string $column ): self {
        $this->referencesColumn = $column;
        return $this;
    }

    public function on( string $table ): self {
        $this->referencesTable = $table;
        return $this;
    }

    /**
     * Shorthand for on($table)->references($column): ties this column to a foreign
     * key. The referenced table must be given explicitly - no pluralization/naming
     * convention is guessed, to avoid silently pointing at the wrong table.
     */
    public function constrained( string $table, string $column = 'id' ): self {
        $this->referencesTable = $table;
        $this->referencesColumn = $column;
        return $this;
    }

    public function onDelete( string $action ): self {
        $this->onDeleteAction = $this->normalizeReferentialAction( $action );
        return $this;
    }

    public function onUpdate( string $action ): self {
        $this->onUpdateAction = $this->normalizeReferentialAction( $action );
        return $this;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getType(): string {
        return $this->type;
    }

    public function getArgs(): array {
        return $this->args;
    }

    public function isNullable(): bool {
        return $this->nullable;
    }

    public function isUnsigned(): bool {
        return $this->unsigned;
    }

    public function isAutoIncrement(): bool {
        return $this->autoIncrement;
    }

    public function isPrimary(): bool {
        return $this->primary;
    }

    public function isUnique(): bool {
        return $this->unique;
    }

    public function hasDefault(): bool {
        return $this->hasDefault;
    }

    public function getDefault() {
        return $this->default;
    }

    public function hasForeignKey(): bool {
        return $this->referencesTable !== null;
    }

    public function getReferencesTable(): ?string {
        return $this->referencesTable;
    }

    public function getReferencesColumn(): string {
        return $this->referencesColumn ?? 'id';
    }

    public function getOnDelete(): ?string {
        return $this->onDeleteAction;
    }

    public function getOnUpdate(): ?string {
        return $this->onUpdateAction;
    }

    private function normalizeReferentialAction( string $action ): string {
        $normalized = strtoupper( str_replace( '_', ' ', $action ) );
        $allowed = [ 'CASCADE', 'RESTRICT', 'SET NULL', 'NO ACTION' ];

        if ( !in_array( $normalized, $allowed, true ) ) {
            throw new \InvalidArgumentException(
                "Unsupported referential action '$action'. Allowed: " . implode( ', ', $allowed )
            );
        }

        return $normalized;
    }

}
