<?php

namespace Fabiom\UglyDuckling\Framework\DataBase\Migrations;

use Fabiom\UglyDuckling\Framework\DataBase\Migrations\Grammars\Grammar;
use Fabiom\UglyDuckling\Framework\DataBase\Migrations\Grammars\MySqlGrammar;
use Fabiom\UglyDuckling\Framework\DataBase\Migrations\Grammars\SQLiteGrammar;
use PDO;
use RuntimeException;

/**
 * Static facade migrations call to build/alter/drop tables through a Blueprint,
 * mirroring Laravel's Schema::create()/Schema::table(). Migrator calls
 * setConnection() before running each migration's up()/down(); this is the one
 * deliberate piece of static state in the migrations subsystem, scoped to a single
 * CLI process - a migration file itself should never call setConnection().
 */
class Schema {

    private static ?PDO $pdo = null;

    public static function setConnection( PDO $pdo ): void {
        self::$pdo = $pdo;
    }

    public static function create( string $table, callable $callback ): void {
        $blueprint = new Blueprint( $table );
        $callback( $blueprint );

        foreach ( self::grammar()->compileCreate( $blueprint ) as $statement ) {
            self::pdo()->exec( $statement );
        }
    }

    public static function table( string $table, callable $callback ): void {
        $blueprint = new Blueprint( $table );
        $callback( $blueprint );

        foreach ( self::grammar()->compileAlter( $blueprint ) as $statement ) {
            self::pdo()->exec( $statement );
        }
    }

    public static function drop( string $table ): void {
        self::pdo()->exec( self::grammar()->compileDrop( $table ) );
    }

    public static function dropIfExists( string $table ): void {
        self::pdo()->exec( self::grammar()->compileDropIfExists( $table ) );
    }

    public static function hasTable( string $table ): bool {
        [ $sql, $bindings ] = self::grammar()->compileHasTable( $table );

        $statement = self::pdo()->prepare( $sql );
        $statement->execute( $bindings );

        return $statement->fetchColumn() !== false;
    }

    /**
     * @return string[] every table name in the current database
     */
    public static function allTableNames(): array {
        $statement = self::pdo()->query( self::grammar()->compileTableNames() );

        return $statement->fetchAll( PDO::FETCH_COLUMN );
    }

    private static function pdo(): PDO {
        if ( self::$pdo === null ) {
            throw new RuntimeException( 'Schema::setConnection() must be called before using Schema.' );
        }

        return self::$pdo;
    }

    private static function grammar(): Grammar {
        $driver = self::pdo()->getAttribute( PDO::ATTR_DRIVER_NAME );

        return $driver === 'sqlite' ? new SQLiteGrammar() : new MySqlGrammar();
    }

}
