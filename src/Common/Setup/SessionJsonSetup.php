<?php

namespace Fabiom\UglyDuckling\Common\Setup;

use Fabiom\UglyDuckling\Common\Database\QueryExecuter;
use Fabiom\UglyDuckling\Common\Json\JsonTemplates\QueryBuilder;
use Fabiom\UglyDuckling\Common\Wrappers\SessionWrapper;

class SessionJsonSetup {
	
	public function loadSessionVariables($sessionSetupPath. $queryBuilder, $queryExecuter, $sessionWrapper) {
		if (file_exists ( $sessionSetupPath )) {
			$handle = fopen($sessionSetupPath, 'r');
			$data = fread($handle,filesize($sessionSetupPath));
			$loadedfile = $this->json_decode_with_error_control($data);


			foreach ($loadedfile->queryset as $query) {
				
			}

			foreach ($loadedfile->sessionvars as $sessionvar) {
				
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
	public function json_decode_with_error_control( string $data ) {
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
