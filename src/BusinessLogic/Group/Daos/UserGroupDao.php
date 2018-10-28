<?php

namespace Firststep\BusinessLogic\Group\Daos;

use Firststep\Common\Database\BasicDao;
use PDO;
use stdClass;

class UserGroupDao extends BasicDao {
	
	const DB_TABLE = 'usergroup';
	const DB_TABLE_PK = 'ug_id';
    const DB_TABLE_UPDATED_FIELD_NAME = 'usr_updated';
    const DB_TABLE_CREATED_FLIED_NAME = 'usr_created';
	
	/*
	  Fields list
	  ug_id                     Primary Key
	  ug_groupslug              slug that belongs to a group set in a json file, it is like a FK
	  ug_userid                 FK user :: usr_id
      ug_updated
      ug_created
	*/
	
	/**
	 * it overloads the getEmpty method of the parent class
	 */
	public function getEmpty() {
		$empty = new stdClass;
		$empty->ug_id        = 0;
		$empty->ug_groupslug = '';
		$empty->ug_userid    = 0;
		$empty->ug_updated   = date( 'Y-m-d' );
		$empty->ug_created   = date( 'Y-m-d' );
		return $empty;
	}
	
	/**
	 * In order to save the password it uses the algorithms created by the community
	 * password_hash("rasmuslerdorf", PASSWORD_DEFAULT);
	 * password_verify('rasmuslerdorf', $hash)
	 */
	function checkEmailAndPassword($email, $password) {
		try {
			$STH = $this->DBH->prepare('SELECT usr_hashedpsw FROM user WHERE usr_email = :email;');
			$STH->bindParam(':email', $email, PDO::PARAM_STR);
			$STH->execute();
			
            $STH->setFetchMode(PDO::FETCH_OBJ);
            $obj = $STH->fetch();

            // user with given email does not exist
            if ($obj == null) {
                return false;
            }
			
			// To fix some password issue:
			// echo 'Password: '.$password.' password hash:'.password_hash($password, PASSWORD_DEFAULT).' dbpassword hash:'.$obj->usr_hashedpsw;
			
			return password_verify($password, $obj->usr_hashedpsw);
		}
		catch(PDOException $e) {
			$logger = new Logger();
			$logger->write($e->getMessage(), __FILE__, __LINE__);
		}
	}

	function updatePassword($id, $password) {
	    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $presentmoment = date('Y-m-d H:i:s', time());

        try {
            $STH = $this->DBH->prepare('UPDATE ' . $this::DB_TABLE . ' SET usr_hashedpsw = :hashedpsw, usr_password_updated = "' . $presentmoment . '" WHERE ' . $this::DB_TABLE_PK . ' = :id');
            $STH->bindParam(':hashedpsw', $hashedPassword);
            $STH->bindParam(':id', $id);
            $STH->execute();
        } catch (PDOException $e) {
            $logger = new Logger();
            $logger->write($e->getMessage(), __FILE__, __LINE__);
            throw new \Exception('General malfuction!!!');
        }
    }

}
