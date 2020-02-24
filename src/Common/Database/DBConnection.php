<?php

namespace Fabiom\UglyDuckling\Common\Database;

use PDO;
use PDOException;
use Fabiom\UglyDuckling\Common\Loggers\Logger;

/**
 * Basic class for all dao's
 */
class DBConnection {

    public /* PDO */ $DBH;
    private /* Logger */ $logger;

    /**
     * Setting up the database connection
     *
     * @param string $host
     * @param string $dbname
     * @param string $username
     * @param string $password
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
            $this->logger->write($e->getMessage(), __FILE__, __LINE__);
            throw new \Exception('General malfuction!!!');
        }   
    }

    /**
     * Begin a transaction, turning off autocommit
     */
    public function beginTransaction() {
        $this->DBH->beginTransaction();
    }

    /**
     * Commits a transaction
     */
    public function commit() {
        $this->DBH->commit();
    }

    /**
     * Recognize mistake and roll back changes, after that put the Database connection in autocommit mode
     */
    public function rollBack() {
        $this->DBH->rollBack();
    }

    /**
     * Set the logger for the classe
     *
     * It is going to receive error messages in case of PDO exception
     * @param Logger $logger
     */
    public function setLogger(Logger $logger) {
        $this->logger = $logger;
    }

}
