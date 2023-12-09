<?php

namespace Fabiom\UglyDuckling\Framework\DataBase;

use PDO;
use PDOException;
use Fabiom\UglyDuckling\Common\Loggers\Logger;

/**
 * Basic class for all dao's
 */
class DBConnection {

    public /* PDO */ $DBH;
    private /* Logger */ $logger;
	private /* string */ $pemFileName;
	private /* string */ $host;
	private /* string */ $dbname;
	private /* string */ $username;
	private /* string */ $password;

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
	
	function setSSLFile( string $pemFileName ) {
		$this->pemFileName = $pemFileName;
	}
	
    /**
     * Database connection handler getter
     * I can use the already made connection for next database call
     */
    public function getDBH() {
        try {
			if ( isset( $this->pemFileName ) AND $this->pemFileName != '' ) {
				$options = array(
					PDO::MYSQL_ATTR_SSL_CA => $this->pemFileName
				);
				$this->DBH = new PDO($this->host . $this->dbname, $this->username, $this->password, $options);
			} else {
				$this->DBH = new PDO($this->host . $this->dbname, $this->username, $this->password);
			}            
			
			$this->DBH->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			return $this->DBH;
        } catch (PDOException $e) {
            echo $e->getMessage();
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
