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
     * Set by Blueprint::id()/increments(); not part of the public fluent API.
     */
    public function markAsAutoIncrementPrimaryKey(): self {
        $this->autoIncrement = true;
        $this->primary = true;
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

}
