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
	usr_defaultgroup
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
