<?php

namespace Firststep\Common\Database;

use PDO;

/**
 * Basic class for all dao's
 */
class DBConnection {

    function __construct( string $host, string $dbname, string $username, string $password ) {
        try {
            $this->DBH = new PDO($host . $dbname, $username, $password);
            $this->DBH->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            $logger = new Logger();
            $logger->write($e->getMessage(), __FILE__, __LINE__);
            throw new \Exception('General malfuction!!!');
        }
    }
	
    /**
     * Database connection handler getter
     * I can use the already made connection for next database call
     */
    public function getDBH() {
        return $this->DBH;
    }

}
