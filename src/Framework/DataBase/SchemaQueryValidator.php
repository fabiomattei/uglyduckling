<?php

namespace Fabiom\UglyDuckling\Framework\DataBase;

/**
 * Validates the SQL embedded in JSON resource files (get.query.sql and
 * post.query.sql) against the schema of a real database connection, by
 * asking the database to PREPARE each query without executing it. This
 * catches unknown tables/columns and syntax errors caused by schema drift.
 *
 * For the mysql driver, PDO::ATTR_EMULATE_PREPARES is forced off, because
 * emulated prepares only parse placeholders client-side and never round-trip
 * to the server - which would defeat the whole check.
 */
class SchemaQueryValidator {

    private \PDO $pdo;

    public function __construct(\PDO $pdo) {
        if ($pdo->getAttribute(\PDO::ATTR_DRIVER_NAME) === 'mysql') {
            $pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
        }
        $this->pdo = $pdo;
    }

    /**
     * @return SchemaQueryValidationError[]
     */
    public function validateDirectory(string $jsonResourcesDirectory): array {
        $errors = [];

        foreach ($this->findJsonFiles($jsonResourcesDirectory) as $file) {
            $errors = array_merge($errors, $this->validateFile($file));
        }

        return $errors;
    }

    /**
     * @return SchemaQueryValidationError[]
     */
    private function validateFile(string $file): array {
        $resource = json_decode(file_get_contents($file));

        if ($resource === null && json_last_error() !== JSON_ERROR_NONE) {
            return [new SchemaQueryValidationError($file, 'json', '', 'Invalid JSON: ' . json_last_error_msg())];
        }
        if (!($resource instanceof \stdClass)) {
            return [];
        }

        $errors = [];
        foreach (['get', 'post'] as $verb) {
            $sql = $resource->$verb->query->sql ?? null;
            if (!is_string($sql) || $sql === '') {
                continue;
            }

            $error = $this->validateSql($file, $verb, $sql);
            if ($error !== null) {
                $errors[] = $error;
            }
        }

        return $errors;
    }

    private function validateSql(string $file, string $verb, string $sql): ?SchemaQueryValidationError {
        try {
            $this->pdo->prepare($sql);
            return null;
        } catch (\PDOException $e) {
            return new SchemaQueryValidationError($file, $verb, $sql, $e->getMessage());
        }
    }

    /**
     * @return string[]
     */
    private function findJsonFiles(string $directory): array {
        $files = [];
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($directory, \FilesystemIterator::SKIP_DOTS)
        );

        foreach ($iterator as $fileInfo) {
            if ($fileInfo->isFile() && $fileInfo->getExtension() === 'json') {
                $files[] = $fileInfo->getPathname();
            }
        }

        sort($files);
        return $files;
    }

}
