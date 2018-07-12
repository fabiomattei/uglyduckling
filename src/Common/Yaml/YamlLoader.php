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
		$indexLoaded = yaml_parse_file($this->indexpath);
		echo $indexLoaded;
	}
	
}
