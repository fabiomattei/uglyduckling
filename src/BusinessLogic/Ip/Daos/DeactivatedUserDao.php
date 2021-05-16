<?php

namespace Fabiom\UglyDuckling\BusinessLogic\Ip\Daos;

use Fabiom\UglyDuckling\Common\Database\BasicDao;
use PDO;
use stdClass;

class DeactivatedUserDao extends BasicDao {

    const DB_TABLE = 'deactivateduser';
    const DB_TABLE_PK = 'du_id';
    const DB_TABLE_CREATED_FLIED_NAME = 'du_created';

    /*
    Elenco campi
    Fields list
    du_id                     Primary Key
    du_username
    du_created
    */

    /**
     * it overloads the getEmpty method of the parent class
     */
    public function getEmpty() {
        $empty = new stdClass;
        $empty->du_id          = 0;
        $empty->du_username    = '';
        return $empty;
    }

    function insertUser( string $username ) {
        try {
            $this->DBH->beginTransaction();
            $STH = $this->DBH->prepare('INSERT INTO deactivateduser (du_username, ' . $this::DB_TABLE_CREATED_FLIED_NAME . ') VALUES (:username, NOW() )');
            $STH->bindParam( ':username', $username, PDO::PARAM_STR );
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

    function checkIfIpIsDeactivated( string $username ) {
        try {
            $STH = $this->DBH->prepare('SELECT du_username FROM deactivateduser WHERE du_username = :username;');
            $STH->bindParam(':username', $username, PDO::PARAM_STR);
            $STH->execute();

            $STH->setFetchMode(PDO::FETCH_OBJ);
            $obj = $STH->fetch();

            // user with given email does not exist
            if ($obj == null) {
                return false;
            }

            return true;
        }
        catch(\PDOException $e) {
            $logger = new Logger();
            $logger->write($e->getMessage(), __FILE__, __LINE__);
        }
    }

}
