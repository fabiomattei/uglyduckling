<?php

namespace Fabiom\UglyDuckling\Framework\BusinessLogic\Ip\Daos;

use Fabiom\UglyDuckling\Framework\Database\BasicDao;
use PDO;
use stdClass;

class SecurityLogDao extends BasicDao {

    const DB_TABLE = 'ud_securitylog';
    const DB_TABLE_PK = 'sl_id';
    const DB_TABLE_CREATED_FLIED_NAME = 'sl_created';

    /*
    Elenco campi
    Fields list
    sl_id                     Primary Key
    sl_ipaddress
    sl_username
    sl_password
    sl_description
    sl_created
    */

    /**
     * it overloads the getEmpty method of the parent class
     */
    public function getEmpty() {
        $empty = new stdClass;
        $empty->sl_id          = 0;
        $empty->sl_ipaddress   = '0.0.0.0';
        $empty->sl_username    = '';
        $empty->sl_password    = '';
        $empty->sl_description = '';
        return $empty;
    }

    function insertEvent( string $remote_address, string $username, string $password, string $description ) {
        try {
            $this->DBH->beginTransaction();
            $STH = $this->DBH->prepare('INSERT INTO '.$this::DB_TABLE.' (sl_ipaddress, sl_username, sl_password, sl_description, ' . $this::DB_TABLE_CREATED_FLIED_NAME . ') VALUES (:ipaddress, :username, :password, :description, NOW() )');
            $STH->bindParam( ':ipaddress', $remote_address, PDO::PARAM_STR );
            $STH->bindParam( ':username', $username, PDO::PARAM_STR );
            $STH->bindParam( ':password', $password, PDO::PARAM_STR );
            $STH->bindParam( ':description', $description, PDO::PARAM_STR );
            $STH->execute();
            $inserted_id = $this->DBH->lastInsertId();
            $this->DBH->commit();
            return $inserted_id;
        } catch (PDOException $e) {
            $this->logger->write($e->getMessage(), __FILE__, __LINE__);
            throw new \Exception('General malfuction!!!');
        }
    }

}
