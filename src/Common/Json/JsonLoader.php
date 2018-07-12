<?php

namespace Firststep\Common\Json;

/**
 * JsonLoader makes an index of all available resources and load the 
 * resource if needed
 */
class JsonLoader {
	
	private $indexpath;
	private $resourcesIndex = array();
	
	function __construct() {
		// empty as you see
	}
	
	public function setIndexPath( $indexpath ) {
		$this->indexpath = $indexpath;
	}
	
	public function loadIndex( $indexPath = '' ) {
		if ($indexPath == '') {
			$indexPath = $this->indexpath;
		}
		if (file_exists ( $indexPath )) {
			$handle = fopen($indexPath, 'r');
			$data = fread($handle,filesize($indexPath));
			$loadedfile = $this->json_decode_with_error_control($data);
			foreach ($loadedfile->scripts as $key) {
				if($key->type == 'index') {
					$this->loadIndex( $key->path );
				} else {
					$this->resourcesIndex[$key->name] = $key->path;
				}
			}
		}
		// var_dump( $this->resourcesIndex );	
	}
	
	/**
	 * Load a resource from file specified with array index
	 */
	public function loadResource( $name = '' ) {
		if (array_key_exists($name, $this->resourcesIndex)) {
		    return $this->resourcesIndex[$name];
		} else {
			return array();
		}
	}
	
	/**
	 * Decoding json string with error control
	 */
	public function json_decode_with_error_control( $data ) {
		$loadeddata = json_decode($data);
		switch (json_last_error()) {
        	case JSON_ERROR_NONE:
        	    // throw new \Exception(' - No errors');
        	break;
        	case JSON_ERROR_DEPTH:
        	    throw new \Exception(' - Maximum stack depth exceeded');
        	break;
        	case JSON_ERROR_STATE_MISMATCH:
        	    throw new \Exception(' - Underflow or the modes mismatch');
        	break;
        	case JSON_ERROR_CTRL_CHAR:
        	    throw new \Exception(' - Unexpected control character found');
        	break;
        	case JSON_ERROR_SYNTAX:
        	    throw new \Exception(' - Syntax error, malformed JSON');
        	break;
        	case JSON_ERROR_UTF8:
        	    throw new \Exception(' - Malformed UTF-8 characters, possibly incorrectly encoded');
        	break;
        	default:
        	    throw new \Exception(' - Unknown error');
        	break;
    	}
		return $loadeddata;
	}
	
}
