<?php

namespace Firststep\Common\Json;

use Firststep\Common\Builders\FormBuilder;

/**
 * JsonLoader makes an index of all available resources and load the 
 * resource if needed
 */
class JsonBlockFormParser {
	
	public static function parse( $resource ) {
		
		
		$par_rules = array();
		$par_filters = array();
		foreach ($resource->request->parameters as $key) {
			$par_rules[$key->name]   = $key->validation;
			$par_filters[$key->name] = 'trim';
		}
		return array( 'rules' => $par_rules, 'filters' => $par_filters );
	}
	
}
