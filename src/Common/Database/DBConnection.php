<?php

namespace Firststep\Common\Database;

use PDO;

/**
 * Basic class for all dao's
 */
class DBConnection {

    /**
     * Setting up the database connection
     */
    function __construct( string $host, string $dbname, string $username, string $password ) {
		$this->host = $host;
		$this->dbname = $dbname;
		$this->username = $username;
		$this->password = $password;
    }
	
    /**
     * Database connection handler getter
     * I can use the already made connection for next database call
     */
    public function getDBH() {
        try {
            $this->DBH = new PDO($this->host . $this->dbname, $this->username, $this->password);
            $this->DBH->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			return $this->DBH;
        } catch (PDOException $e) {
            $logger = new Logger();
            $logger->write($e->getMessage(), __FILE__, __LINE__);
            throw new \Exception('General malfuction!!!');
        }   
    }

}
