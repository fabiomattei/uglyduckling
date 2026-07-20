<?php

namespace Fabiom\UglyDuckling\Framework\DataBase\Migrations\Grammars;

use Fabiom\UglyDuckling\Framework\DataBase\Migrations\Blueprint;
use Fabiom\UglyDuckling\Framework\DataBase\Migrations\ColumnDefinition;

class SQLiteGrammar extends Grammar {

    public function compileHasTable( string $table ): array {
        return [
            "SELECT 1 FROM sqlite_master WHERE type = 'table' AND name = ?",
            [ $table ],
        ];
    }

    public function compileTableNames(): string {
        return "SELECT name FROM sqlite_master WHERE type = 'table' AND name NOT LIKE 'sqlite\_%' ESCAPE '\'";
    }

    /**
     * SQLite only wires up its AUTOINCREMENT rowid alias when the column type is the
     * literal token "INTEGER" (not "BIGINT", even though both share integer affinity),
     * so unsigned/type args are intentionally ignored here.
     */
    protected function autoIncrementPrimaryKeyColumn( ColumnDefinition $column ): string {
        return $this->quoteIdentifier( $column->getName() ) . ' INTEGER PRIMARY KEY AUTOINCREMENT';
    }

    /**
     * SQLite cannot add a table-level constraint via ALTER TABLE, but (unlike MySQL)
     * it does enforce a REFERENCES clause written inline on the new column's own
     * definition, as long as `PRAGMA foreign_keys = ON` is set on the connection -
     * see Schema::setConnection().
     */
    protected function compileAddColumnStatements( Blueprint $blueprint, ColumnDefinition $column ): array {
        $sql = 'ALTER TABLE ' . $this->quoteIdentifier( $blueprint->getTable() )
            . ' ADD COLUMN ' . $this->compileColumn( $column );

        if ( $column->hasForeignKey() ) {
            $sql .= ' REFERENCES ' . $this->quoteIdentifier( $column->getReferencesTable() )
                . ' (' . $this->quoteIdentifier( $column->getReferencesColumn() ) . ')'
                . $this->compileReferentialActions( $column );
        }

        return [ $sql ];
    }

}
