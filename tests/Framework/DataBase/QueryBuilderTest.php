<?php

use Fabiom\UglyDuckling\Framework\DataBase\QueryBuilder;
use Fabiom\UglyDuckling\Framework\Loggers\MuteLogger;

/**
 * Testing QueryBuilder against an in-memory SQLite database with two joined tables
 */
class QueryBuilderTest extends PHPUnit\Framework\TestCase {

    private PDO $pdo;

    protected function setUp(): void {
        $this->pdo = new PDO('sqlite::memory:');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $this->pdo->exec('CREATE TABLE customers (id INTEGER PRIMARY KEY, name TEXT, country TEXT)');
        $this->pdo->exec('CREATE TABLE orders (id INTEGER PRIMARY KEY, customer_id INTEGER, status TEXT, total INTEGER)');

        $this->pdo->exec("INSERT INTO customers (id, name, country) VALUES (1, 'Alice', 'IT'), (2, 'Bob', 'US')");
        $this->pdo->exec("INSERT INTO orders (id, customer_id, status, total) VALUES
            (1, 1, 'paid', 100),
            (2, 1, 'pending', 50),
            (3, 2, 'paid', 200)");
    }

    private function newQuery(string $table): QueryBuilder {
        return new QueryBuilder($this->pdo, new MuteLogger(), $table);
    }

    public function testJoinAndWhereReturnMatchingRowsOnly() {
        $rows = $this->newQuery('orders')
            ->select('orders.id', 'customers.name AS customer_name')
            ->join('customers', 'orders.customer_id = customers.id')
            ->where('orders.status', '=', 'paid')
            ->orderBy('orders.id')
            ->get()
            ->fetchAll();

        $this->assertCount(2, $rows);
        $this->assertEquals('Alice', $rows[0]->customer_name);
        $this->assertEquals('Bob', $rows[1]->customer_name);
    }

    public function testWhereBindsSameFieldNameFromDifferentTablesIndependently() {
        $rows = $this->newQuery('orders')
            ->select('orders.id')
            ->join('customers', 'orders.customer_id = customers.id')
            ->where('orders.status', '=', 'paid')
            ->where('customers.country', '=', 'IT')
            ->get()
            ->fetchAll();

        $this->assertCount(1, $rows);
        $this->assertEquals(1, $rows[0]->id);
    }

    public function testLimit() {
        $rows = $this->newQuery('orders')->orderBy('id')->limit(1)->get()->fetchAll();

        $this->assertCount(1, $rows);
    }

    public function testFirstReturnsNullWhenNoRowMatches() {
        $row = $this->newQuery('orders')->where('status', '=', 'missing')->first();

        $this->assertNull($row);
    }

    public function testFirstReturnsSingleRow() {
        $row = $this->newQuery('orders')->where('id', '=', 1)->first();

        $this->assertEquals('paid', $row->status);
    }

    public function testCountRespectsJoinAndWhere() {
        $count = $this->newQuery('orders')
            ->join('customers', 'orders.customer_id = customers.id')
            ->where('customers.country', '=', 'IT')
            ->count();

        $this->assertEquals(2, $count);
    }

    public function testWhereRawAllowsCustomBoundConditions() {
        $rows = $this->newQuery('orders')
            ->whereRaw('total BETWEEN :qb_min AND :qb_max', [':qb_min' => 60, ':qb_max' => 150])
            ->get()
            ->fetchAll();

        $this->assertCount(1, $rows);
        $this->assertEquals(100, $rows[0]->total);
    }

}
