<?php

namespace Fabiom\UglyDuckling\Framework\DataBase\Migrations\Grammars;

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

}
