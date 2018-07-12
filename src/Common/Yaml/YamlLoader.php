<?php

namespace Firststep\Common\Yaml;

/**
* Description
*/
class YamlLoader {
	
	private $indexpath;
	
	function __construct() {
		// empty as you see
	}
	
	public function setIndexPath( $indexpath ) {
		$this->indexpath = $indexpath;
	}
	
	public function loadIndex() {
		$handle = fopen($this->indexpath, 'r');
		$data = fread($handle,filesize($this->indexpath));
		echo $data;
		echo "stoper scrivere";
		var_dump( json_decode($data));
		switch (json_last_error()) {
        case JSON_ERROR_NONE:
            echo ' - No errors';
        break;
        case JSON_ERROR_DEPTH:
            echo ' - Maximum stack depth exceeded';
        break;
        case JSON_ERROR_STATE_MISMATCH:
            echo ' - Underflow or the modes mismatch';
        break;
        case JSON_ERROR_CTRL_CHAR:
            echo ' - Unexpected control character found';
        break;
        case JSON_ERROR_SYNTAX:
            echo ' - Syntax error, malformed JSON';
        break;
        case JSON_ERROR_UTF8:
            echo ' - Malformed UTF-8 characters, possibly incorrectly encoded';
        break;
        default:
            echo ' - Unknown error';
        break;
    }
	}
	
}
