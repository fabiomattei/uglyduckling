<?php

namespace Fabiom\UglyDuckling\Framework\DataBase\Migrations\Grammars;

use Fabiom\UglyDuckling\Framework\DataBase\Migrations\Blueprint;
use Fabiom\UglyDuckling\Framework\DataBase\Migrations\ColumnDefinition;

class MySqlGrammar extends Grammar {

    public function quoteIdentifier( string $name ): string {
        return '`' . str_replace( '`', '``', $name ) . '`';
    }

    public function compileHasTable( string $table ): array {
        return [
            'SELECT 1 FROM information_schema.tables WHERE table_schema = DATABASE() AND table_name = ?',
            [ $table ],
        ];
    }

    public function compileTableNames(): string {
        return 'SELECT table_name FROM information_schema.tables WHERE table_schema = DATABASE()';
    }

    protected function autoIncrementPrimaryKeyColumn( ColumnDefinition $column ): string {
        return $this->quoteIdentifier( $column->getName() ) . ' BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY';
    }

    /**
     * MySQL/InnoDB ignores a REFERENCES clause written inline on a column definition -
     * it only creates and enforces a foreign key from a table-level FOREIGN KEY clause,
     * added here as a second statement since ALTER TABLE ADD COLUMN can't carry one.
     *
     * @return string[]
     */
    protected function compileAddColumnStatements( Blueprint $blueprint, ColumnDefinition $column ): array {
        $statements = parent::compileAddColumnStatements( $blueprint, $column );

        if ( $column->hasForeignKey() ) {
            $statements[] = 'ALTER TABLE ' . $this->quoteIdentifier( $blueprint->getTable() )
                . ' ADD ' . $this->compileForeignKeyClause( $column, $this->foreignKeyConstraintName( $blueprint, $column ) );
        }

        return $statements;
    }

}
