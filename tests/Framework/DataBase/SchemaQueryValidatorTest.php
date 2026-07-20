<?php

use Fabiom\UglyDuckling\Framework\DataBase\SchemaQueryValidator;

/**
 * Testing SchemaQueryValidator against a real in-memory SQLite schema
 */
class SchemaQueryValidatorTest extends PHPUnit\Framework\TestCase {

    private PDO $pdo;
    private SchemaQueryValidator $validator;

    protected function setUp(): void {
        $this->pdo = new PDO('sqlite::memory:');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->exec('CREATE TABLE widgets (id INTEGER PRIMARY KEY, name TEXT)');

        $this->validator = new SchemaQueryValidator($this->pdo);
    }

    public function testValidQueryProducesNoErrors() {
        $errors = $this->validator->validateDirectory(__DIR__ . '/fixtures/json/valid-only');

        $this->assertEquals([], $errors);
    }

    public function testQueryReferencingUnknownColumnIsReported() {
        $errors = $this->validator->validateDirectory(__DIR__ . '/fixtures/json/invalid-only');

        $this->assertCount(1, $errors);
        $this->assertStringContainsString('invalid-resource.json', $errors[0]->file);
        $this->assertEquals('get', $errors[0]->verb);
        $this->assertStringContainsString('nickname', $errors[0]->sql);
    }

    public function testDirectoryIsScannedRecursivelyAndOnlyBadQueriesAreReported() {
        $errors = $this->validator->validateDirectory(__DIR__ . '/fixtures/json');

        $this->assertCount(1, $errors);
        $this->assertStringContainsString('invalid-resource.json', $errors[0]->file);
    }

}
