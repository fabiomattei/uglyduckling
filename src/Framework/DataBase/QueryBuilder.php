<?php

namespace Fabiom\UglyDuckling\Framework\DataBase;

use Fabiom\UglyDuckling\Framework\Loggers\Logger;

/**
 * Fluent SELECT query builder for cases BasicDao does not cover: joins across
 * tables and ad-hoc field/order selection. It only builds and runs SELECT
 * statements - no ORM hydration, no INSERT/UPDATE/DELETE.
 *
 * Table names, join conditions, select fields and order-by fields are treated
 * as trusted developer-supplied SQL fragments (PDO cannot bind identifiers).
 * Only where() values are bound as query parameters.
 */
class QueryBuilder {

    private \PDO $DBH;
    private Logger $logger;
    private string $table;

    private array $selectFields = ['*'];
    private array $joins = [];
    private array $whereConditions = [];
    private array $bindings = [];
    private array $orderByFields = [];
    private ?int $limitCount = null;
    private int $paramCounter = 0;

    public function __construct(\PDO $DBH, Logger $logger, string $table) {
        $this->DBH = $DBH;
        $this->logger = $logger;
        $this->table = $table;
    }

    public function select(string ...$fields): static {
        $this->selectFields = $fields;
        return $this;
    }

    public function join(string $table, string $onCondition, string $type = 'INNER'): static {
        $this->joins[] = strtoupper($type) . ' JOIN ' . $table . ' ON ' . $onCondition;
        return $this;
    }

    public function leftJoin(string $table, string $onCondition): static {
        return $this->join($table, $onCondition, 'LEFT');
    }

    public function where(string $field, string $operator, $value): static {
        $placeholder = 'qb_param_' . $this->paramCounter++;
        $this->whereConditions[] = $field . ' ' . $operator . ' :' . $placeholder;
        $this->bindings[$placeholder] = $value;
        return $this;
    }

    /**
     * Escape hatch for conditions that don't fit field/operator/value, e.g.
     * "orders.total BETWEEN :qb_min AND :qb_max". Caller must supply and bind
     * its own placeholder names via $bindings.
     */
    public function whereRaw(string $rawSql, array $bindings = []): static {
        $this->whereConditions[] = $rawSql;
        foreach ($bindings as $placeholder => $value) {
            $this->bindings[$placeholder] = $value;
        }
        return $this;
    }

    public function orderBy(string ...$fields): static {
        $this->orderByFields = $fields;
        return $this;
    }

    public function limit(int $count): static {
        $this->limitCount = $count;
        return $this;
    }

    /**
     * Runs the built SELECT and returns the statement, fetch mode FETCH_OBJ
     * (same contract as BasicDao's get* methods).
     */
    public function get(): \PDOStatement {
        try {
            $STH = $this->DBH->prepare($this->buildSelectSql());
            $this->bindWhereValues($STH);
            $STH->execute();
            $STH->setFetchMode(\PDO::FETCH_OBJ);

            return $STH;
        } catch (\PDOException $e) {
            $this->logger->write($e->getMessage(), __FILE__, __LINE__);
            throw new \Exception('Database operation failed: ' . $e->getMessage(), (int) $e->getCode(), $e);
        }
    }

    public function first(): ?object {
        $original = $this->limitCount;
        $this->limitCount = 1;
        $row = $this->get()->fetch();
        $this->limitCount = $original;

        return $row === false ? null : $row;
    }

    public function count(): int {
        try {
            $sql = 'SELECT COUNT(*) as countresult FROM ' . $this->table . ' ' . implode(' ', $this->joins);
            if ($this->whereConditions !== []) {
                $sql .= ' WHERE ' . implode(' AND ', $this->whereConditions);
            }

            $STH = $this->DBH->prepare($sql);
            $this->bindWhereValues($STH);
            $STH->execute();
            $STH->setFetchMode(\PDO::FETCH_OBJ);

            $row = $STH->fetch();
            return $row === false ? 0 : (int) $row->countresult;
        } catch (\PDOException $e) {
            $this->logger->write($e->getMessage(), __FILE__, __LINE__);
            throw new \Exception('Database operation failed: ' . $e->getMessage(), (int) $e->getCode(), $e);
        }
    }

    private function buildSelectSql(): string {
        $sql = 'SELECT ' . implode(', ', $this->selectFields) . ' FROM ' . $this->table;

        if ($this->joins !== []) {
            $sql .= ' ' . implode(' ', $this->joins);
        }
        if ($this->whereConditions !== []) {
            $sql .= ' WHERE ' . implode(' AND ', $this->whereConditions);
        }
        if ($this->orderByFields !== []) {
            $sql .= ' ORDER BY ' . implode(', ', $this->orderByFields);
        }
        if ($this->limitCount !== null) {
            $sql .= ' LIMIT ' . $this->limitCount;
        }

        return $sql;
    }

    private function bindWhereValues(\PDOStatement $STH): void {
        foreach ($this->bindings as $placeholder => $value) {
            $STH->bindValue($placeholder, $value);
        }
    }

}
