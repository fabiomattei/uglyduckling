<?php

namespace Fabiom\UglyDuckling\Framework\BusinessLogic\User\Daos;

use Fabiom\UglyDuckling\Framework\DataBase\BasicDao;
use PDO;
use stdClass;

class UserDao extends BasicDao {
	
	const DB_TABLE = 'ud_users';
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


	public function makeListForDropdown() {
        $query = 'SELECT U.usr_id, U.usr_name, U.usr_surname FROM '.$this::DB_TABLE.' as U '.
            ' ORDER BY U.usr_name, U.usr_surname  ';  // trick to have the offices at the top of the list
        try {
            $STH = $this->DBH->prepare( $query );
            $STH->execute();

            # setting the fetch mode
            $STH->setFetchMode(PDO::FETCH_OBJ);

            $usersForDropDown = array();
            foreach ($STH as $user) {
                $usersForDropDown[$user->usr_id] = $user->usr_name.' '.$user->usr_surname;
            }

            return $usersForDropDown;
        }
        catch(\PDOException $e) {
            $this->logger->write($e->getMessage(), __FILE__, __LINE__);
        }
    }
	
	/**
	 * In order to save the password it uses the algorithms created by the community
	 * password_hash("rasmuslerdorf", PASSWORD_DEFAULT);
	 * password_verify('rasmuslerdorf', $hash)
	 */
	function checkEmailAndPassword($email, $password) {
		try {
			$STH = $this->DBH->prepare('SELECT usr_hashedpsw FROM ud_users WHERE usr_email = :email;');
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
		catch(\PDOException $e) {
			$this->logger->write($e->getMessage(), __FILE__, __LINE__);
		}
	}

    /**
     * Return true if email exists and usr_deactivated is equal to 0 and false otherwise
     */
    function checkUserIsActive($email) {
        try {
            $STH = $this->DBH->prepare('SELECT usr_hashedpsw FROM ud_users WHERE usr_email = :email AND usr_deactivated = 0;');
            $STH->bindParam(':email', $email, PDO::PARAM_STR);
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
            $this->logger->write($e->getMessage(), __FILE__, __LINE__);
        }
    }

    /**
     * In order to save the password it uses the algorithms created by the community
     * password_hash("rasmuslerdorf", PASSWORD_DEFAULT);
     * password_verify('rasmuslerdorf', $hash)
     */
    function checkUserNameAndPassword($username, $password) {
        try {
            $STH = $this->DBH->prepare('SELECT usr_hashedpsw FROM ud_users WHERE usr_username = :username;');
            $STH->bindParam(':username', $username, PDO::PARAM_STR);
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
        catch(\PDOException $e) {
            $this->logger->write($e->getMessage(), __FILE__, __LINE__);
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
        } catch (\PDOException $e) {
            $this->logger->write($e->getMessage(), __FILE__, __LINE__);
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
