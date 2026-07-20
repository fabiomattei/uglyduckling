<?php

namespace Fabiom\UglyDuckling\Framework\DataBase;

/**
 * One SQL query, found inside a JSON resource file, that failed to prepare
 * against the schema of the connection passed to SchemaQueryValidator.
 */
final class SchemaQueryValidationError {

    public function __construct(
        public readonly string $file,
        public readonly string $verb,
        public readonly string $sql,
        public readonly string $message
    ) {}

    public function __toString(): string {
        return '[' . $this->verb . '] ' . $this->file . ': ' . $this->message;
    }

}
