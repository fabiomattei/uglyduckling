<?php

namespace Fabiom\UglyDuckling\Common\Setup;

use Fabiom\UglyDuckling\Common\Database\QueryExecuter;
use Fabiom\UglyDuckling\Common\Database\QuerySet;
use Fabiom\UglyDuckling\Common\Wrappers\SessionWrapper;

class SessionJsonSetup {

    /**
     * Loading all necessary session variables
     * To be used during login process
     *
     * @param string $sessionSetupPath
     * @param QueryExecuter $queryExecutor
     * @param SessionWrapper $sessionWrapper
     */
	public static function loadSessionVariables(string $sessionSetupPath, QueryExecuter $queryExecutor, SessionWrapper $sessionWrapper) {
		if (file_exists ( $sessionSetupPath )) {
			$handle = fopen($sessionSetupPath, 'r');
			$data = fread($handle,filesize($sessionSetupPath));
			$loadedfile = SessionJsonSetup::json_decode_with_error_control($data);

			$querySet = new QuerySet;

			foreach ($loadedfile->queryset as $query) {
                $queryExecutor->setQueryStructure($query);
                $result = $queryExecutor->executeSql();
                $entity = $result->fetch();
                if (isset($query->label)) {
                    $querySet->setResult($query->label, $entity);
                } else {
                    $querySet->setResultNoKey($entity);
                }
			}

			foreach ($loadedfile->sessionvars as $sessionvar) {
                if ( isset( $sessionvar->querylabel ) AND isset( $sessionvar->sqlfield ) ) {
                    if ( isset($querySet->getResult($sessionvar->querylabel)->{$sessionvar->sqlfield}) ) {
                        $sessionWrapper->setSessionParameter($sessionvar->name, $querySet->getResult($sessionvar->querylabel)->{$sessionvar->sqlfield} );
                    }
                }
                if ( isset( $sessionvar->constantparamenter ) ) {
                    $sessionWrapper->setSessionParameter($sessionvar->name, $sessionvar->constantparamenter);
                }
			}
		}
	}

	/**
     * Decode json string with error control
     *
     * based on json_decode, it builds a php structure based on the json structure.
     * throws exceptions
     *
     * @param $data string that contains the json structure
     *
     * @return mixed, a php structure that mirrors the json structure
     *
     * @throws \InvalidArgumentException after the error check
     * JSON_ERROR_DEPTH
     * JSON_ERROR_STATE_MISMATCH
     * JSON_ERROR_CTRL_CHAR
     * JSON_ERROR_SYNTAX
     * JSON_ERROR_UTF8
     *
     */
	public static function json_decode_with_error_control( string $data ) {
		$loadeddata = json_decode($data);
		switch (json_last_error()) {
        	case JSON_ERROR_NONE:
        	    // throw new \Exception(' - No errors');
        	break;
        	case JSON_ERROR_DEPTH:
        	    throw new \InvalidArgumentException('[JsonLoader json_decode error] :: Maximum stack depth exceeded ::'. json_last_error_msg());
        	break;
        	case JSON_ERROR_STATE_MISMATCH:
        	    throw new \InvalidArgumentException('[JsonLoader json_decode error] :: Underflow or the modes mismatch ::'. json_last_error_msg());
        	break;
        	case JSON_ERROR_CTRL_CHAR:
        	    throw new \InvalidArgumentException('[JsonLoader json_decode error] :: Unexpected control character found ::'. json_last_error_msg());
        	break;
        	case JSON_ERROR_SYNTAX:
        	    throw new \InvalidArgumentException('[JsonLoader json_decode error] :: Syntax error, malformed JSON ::'. json_last_error_msg());
        	break;
        	case JSON_ERROR_UTF8:
        	    throw new \InvalidArgumentException('[JsonLoader json_decode error] :: Malformed UTF-8 characters, possibly incorrectly encoded ::'. json_last_error_msg());
        	break;
        	default:
        	    throw new \InvalidArgumentException('[JsonLoader json_decode error] :: Unknown error ::'. json_last_error_msg());
        	break;
    	}
		return $loadeddata;
	}
	
}
