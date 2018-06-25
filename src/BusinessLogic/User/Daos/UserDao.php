<?php

namespace Firststep\BusinessLogic\User\Daos;

use Firststep\Common\Database\BasicDao;
use PDO;
use stdClass;

class UserDao extends BasicDao {
	
	const DB_TABLE = 'user';
	const DB_TABLE_PK = 'usr_id';
    const DB_TABLE_UPDATED_FIELD_NAME = 'usr_updated';
    const DB_TABLE_CREATED_FLIED_NAME = 'usr_created';
	
	/*
	Elenco campi
	Fields list
	usr_id                     Primary Key
	usr_usrofid                FK usroffice :: usrof_id  // id of user main office
	usr_siteid
	usr_name
	usr_surname
	usr_email
	usr_salt
	usr_hashedpsw
	usr_password_updated
    usr_updated
    usr_created
	*/
	
	/**
	 * it overloads the getEmpty method of the parent class
	 */
	public function getEmpty() {
		$empty = new stdClass;
		$empty->usr_id        = 0;
		$empty->usr_usrofid   = 0;
		$empty->usr_name      = '';
		$empty->usr_surname   = '';
		$empty->usr_email     = '';
		$empty->usr_salt      = '';
		$empty->usr_hashedpsw = '';
		$empty->usr_password_updated = date( 'Y-m-d' );
		return $empty;
	}
	
	/**
	 * In order to save the password it uses the algorithms greated by the community
	 * password_hash("rasmuslerdorf", PASSWORD_DEFAULT);
	 * password_verify('rasmuslerdorf', $hash)
	 *
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
			
			// To correct the password
			// echo 'Password: '.password_hash($password, PASSWORD_DEFAULT).' ???? '.$obj->usr_hashedpsw;
			
			return password_verify($password, $obj->usr_hashedpsw);
		}
		catch(PDOException $e) {
			$logger = new Logger();
			$logger->write($e->getMessage(), __FILE__, __LINE__);
		}
	}
	
	// ****************
	// Static section
	// ****************
	
	public static function generatePassword($length = 8) {
        $password = "";
        $possible = "0123456789abcdfghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";

        $i = 0;
        while ($i < $length) {
            $char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
            if (!strstr($password, $char)) {
                $password .= $char;
                $i++;
            }
        }
        return $password;
    }

}
