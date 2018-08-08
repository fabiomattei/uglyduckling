<?php

namespace Firststep\Common\Json;
use stdClass;

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
					$this->resourcesIndex[$key->name] = new stdClass;
					$this->resourcesIndex[$key->name]->path = $key->path;
					$this->resourcesIndex[$key->name]->type = $key->type;
					$this->resourcesIndex[$key->name]->name = $key->name;
				}
			}
		}
		// var_dump( $this->resourcesIndex );	
	}
	
	/**
	 * Gives a list of resources loaded
	 */
	public function getResourcesIndex() {
		return $this->resourcesIndex;
	}
	
	/**
	 * Load a resource from file specified with array index
	 */
	public function loadResource( $key = '' ) {
		if ( array_key_exists( $key, $this->resourcesIndex ) ) {
			if ( file_exists( $this->resourcesIndex[$key]->path ) ) {
				$handle = fopen($this->resourcesIndex[$key]->path, 'r');
				return $this->json_decode_with_error_control(fread($handle,filesize($this->resourcesIndex[$key]->path)));
			} else {
				throw new \Exception('[JsonLoader] :: File associated to resource does not exists!!!');
			}
		} else {
			throw new \Exception('[JsonLoader] :: Resource undefined in array index!!!');
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
        	    throw new \Exception('[JsonLoader] :: Maximum stack depth exceeded');
        	break;
        	case JSON_ERROR_STATE_MISMATCH:
        	    throw new \Exception('[JsonLoader] :: Underflow or the modes mismatch');
        	break;
        	case JSON_ERROR_CTRL_CHAR:
        	    throw new \Exception('[JsonLoader] :: Unexpected control character found');
        	break;
        	case JSON_ERROR_SYNTAX:
        	    throw new \Exception('[JsonLoader] :: Syntax error, malformed JSON');
        	break;
        	case JSON_ERROR_UTF8:
        	    throw new \Exception('[JsonLoader] :: Malformed UTF-8 characters, possibly incorrectly encoded');
        	break;
        	default:
        	    throw new \Exception('[JsonLoader] :: Unknown error');
        	break;
    	}
		return $loadeddata;
	}
	
}
