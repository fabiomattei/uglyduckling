<?php

namespace Fabiom\UglyDuckling\BusinessLogic\Ip\Daos;

use Fabiom\UglyDuckling\Common\Database\BasicDao;
use PDO;
use stdClass;

class IpDao extends BasicDao {
	
	const DB_TABLE = 'ud_blockedip';
	const DB_TABLE_PK = 'ip_id';
    const DB_TABLE_UPDATED_FIELD_NAME = 'ip_updated';
    const DB_TABLE_CREATED_FLIED_NAME = 'ip_created';
	
	/*
	Elenco campi
	Fields list
	ip_id                     Primary Key
	ip_ipaddress
	ip_failed_attepts
	ip_time_to_remove
    ip_updated
    ip_created
	*/
	
	/**
	 * it overloads the getEmpty method of the parent class
	 */
	public function getEmpty() {
		$empty = new stdClass;
		$empty->ip_id             = 0;
		$empty->ip_ipaddress      = '0.0.0.0';
		$empty->ip_failed_attepts = 0;
		$empty->ip_time_to_remove = 0;
		return $empty;
	}
	
	/**
	 * This method queries the database in order to control if the IP attempting to login
     * is contained in the blocked IP list and if the time to remove has passed.
     *
     * If it is contained returns true
     * otherwise it returns false
     *
     * @param string $remote_address
     * @return bool
	 */
	function checkIfIpIsBlocked( string $remote_address ) {
		try {
			$STH = $this->DBH->prepare('SELECT ip_ipaddress FROM '.$this::DB_TABLE.' WHERE ip_ipaddress = :ipaddress AND ip_failed_attepts > 5 AND NOW() < ip_time_to_remove;');
			$STH->bindParam(':ipaddress', $remote_address, PDO::PARAM_STR);
			$STH->execute();
			
            $STH->setFetchMode(PDO::FETCH_OBJ);
            $obj = $STH->fetch();

            if ($obj == null) {
                return false; // IP not contained
            }
			return true; // IP contained
		}
		catch(\PDOException $e) {
			$logger = new Logger();
			$logger->write($e->getMessage(), __FILE__, __LINE__);
		}
	}

    /**
     * This method insert an IP in the blokedip table
     *
     * @param string $remote_address
     * @param int $failedAttempts
     * @return mixed
     * @throws \Exception
     *
     */
	function insertIp( string $remote_address, int $failedAttempts = 1 ) {
        try {
            $this->DBH->beginTransaction();
            $STH = $this->DBH->prepare('INSERT INTO '.$this::DB_TABLE.' (ip_ipaddress, ip_failed_attepts, ip_time_to_remove, ' . $this::DB_TABLE_UPDATED_FIELD_NAME . ', ' . $this::DB_TABLE_CREATED_FLIED_NAME . ') VALUES (:ipaddress, :failedattempts, NOW() + INTERVAL 1 DAY, NOW(), NOW() )');
            $STH->bindParam( ':ipaddress', $remote_address, PDO::PARAM_STR );
            $STH->bindParam( ':failedattempts', $failedAttempts, PDO::PARAM_INT );
            $STH->execute();
            $inserted_id = $this->DBH->lastInsertId();
            $this->DBH->commit();
            return $inserted_id;
        } catch (PDOException $e) {
            $logger = new Logger();
            $logger->write($e->getMessage(), __FILE__, __LINE__);
            throw new \Exception('General malfuction!!!');
        }
	}

    /**
     * At any failed attempt to login to the system, the time required for a new attempt is going to increase by one day
     *
     * @param int $ip_id
     * @throws \Exception
     */
	function delayIp( int $ip_id ) {
        try {
            $this->DBH->beginTransaction();
            $STH = $this->DBH->prepare('UPDATE '.$this::DB_TABLE.' SET ip_time_to_remove = ip_time_to_remove + INTERVAL 1 DAY, ip_failed_attepts = ip_failed_attepts + 1, ' . $this::DB_TABLE_UPDATED_FIELD_NAME . ' = NOW() WHERE ip_id = :ipid');
			$STH->bindParam( ':ipid', $ip_id, PDO::PARAM_INT );
            $STH->execute();
            $this->DBH->commit();
        } catch (PDOException $e) {
            $logger = new Logger();
            $logger->write($e->getMessage(), __FILE__, __LINE__);
            throw new \Exception('General malfuction!!!');
        }
	}

    /**
     * At any failed attempt to login to the system the counter get increased
     *
     * @param int $ip_id
     * @throws \Exception
     */
    function incrementIpCounting( int $ip_id ) {
        try {
            $this->DBH->beginTransaction();
            $STH = $this->DBH->prepare('UPDATE '.$this::DB_TABLE.' SET ip_failed_attepts = ip_failed_attepts + 1, ' . $this::DB_TABLE_UPDATED_FIELD_NAME . ' = NOW() WHERE ip_id = :ipid');
            $STH->bindParam( ':ipid', $ip_id, PDO::PARAM_INT );
            $STH->execute();
            $this->DBH->commit();
        } catch (PDOException $e) {
            $logger = new Logger();
            $logger->write($e->getMessage(), __FILE__, __LINE__);
            throw new \Exception('General malfuction!!!');
        }
    }

    /**
     * Gets all blocked IPS in the table
     *
     * @param string $remote_address
     * @return stdClass|null
     */
	function getByIpAddress( string $remote_address ) {
        $query = 'SELECT * FROM '.$this::DB_TABLE.' WHERE ip_ipaddress = :ipaddress;';
        try {
            $STH = $this->DBH->prepare( $query );
            $STH->bindParam( ':ipaddress', $remote_address, PDO::PARAM_STR );

            $STH->execute();

            # setting the fetch mode
            $STH->setFetchMode(PDO::FETCH_OBJ);
            $obj = $STH->fetch();

            if ($obj == null) {
                $obj = $this->getEmpty();
            }

            return $obj;
        }
        catch(\PDOException $e) {
            $logger = new Logger();
            $logger->write($e->getMessage(), __FILE__, __LINE__);
        }
	}

}
