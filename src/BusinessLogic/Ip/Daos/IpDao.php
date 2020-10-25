<?php

namespace Fabiom\UglyDuckling\BusinessLogic\Ip\Daos;

use Fabiom\UglyDuckling\Common\Database\BasicDao;
use PDO;
use stdClass;

class IpDao extends BasicDao {
	
	const DB_TABLE = 'blockedip';
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
	 * 
	 */
	function checkIfIpIsBlocker( $remote_address ) {
		try {
			
			$STH = $this->DBH->prepare('SELECT ip_ipaddress FROM blockedip WHERE ip_ipaddress = :ipaddress AND ip_time_to_remove < NOW();');
			$STH->bindParam(':ipaddress', $remote_address, PDO::PARAM_STR);
			$STH->execute();
			
            $STH->setFetchMode(PDO::FETCH_OBJ);
            $obj = $STH->fetch();

            // user with given email does not exist
            if ($obj == null) {
                return false;
            }
			
			return true;
		}
		catch(PDOException $e) {
			$logger = new Logger();
			$logger->write($e->getMessage(), __FILE__, __LINE__);
		}
	}
	
	function insertIp( string $remote_address ) {
        try {
            $this->DBH->beginTransaction();
            $STH = $this->DBH->prepare('INSERT INTO blockedip (ip_ipaddress, ip_failed_attepts, ip_time_to_remove, ' . $this::DB_TABLE_UPDATED_FIELD_NAME . ', ' . $this::DB_TABLE_CREATED_FLIED_NAME . ') VALUES (:ipaddress, 1, NOW() + INTERVAL 1 DAY, NOW(), NOW() )');
            $STH->bindParam( ':ipaddress', $remote_address, PDO::PARAM_STR );
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
	
	function delayIp( string $remote_address, int $ip_id ) {
        try {
            $this->DBH->beginTransaction();
            $STH = $this->DBH->prepare('UPDATE blockedip SET ip_time_to_remove = ip_time_to_remove + INTERVAL 1 DAY, ip_failed_attepts = ip_failed_attepts + 1, ' . $this::DB_TABLE_UPDATED_FIELD_NAME . ' = NOW()');
            $STH->bindParam( ':ipaddress', $remote_address, PDO::PARAM_STR );
			$STH->bindParam( ':ipid', $ip_id, PDO::PARAM_INT );
            $STH->execute();
            $this->DBH->commit();
        } catch (PDOException $e) {
            $logger = new Logger();
            $logger->write($e->getMessage(), __FILE__, __LINE__);
            throw new \Exception('General malfuction!!!');
        }
	}
	
	function getByIpAddress( string $remote_address ) {
        $query = 'SELECT * FROM blockedip WHERE ip_ipaddress = :ipaddress;';
        try {
            $STH = $this->DBH->prepare( $query );
            $STH->bindParam( ':ipaddress', $remote_address, PDO::PARAM_STR );

            $STH->execute();

            # setting the fetch mode
            $STH->setFetchMode(PDO::FETCH_OBJ);

            return $STH;
        }
        catch(PDOException $e) {
            $logger = new Logger();
            $logger->write($e->getMessage(), __FILE__, __LINE__);
        }
	}

}
