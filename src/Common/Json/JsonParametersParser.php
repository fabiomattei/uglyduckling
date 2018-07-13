<?php

namespace Firststep\Common\Json;

/**
 * JsonLoader makes an index of all available resources and load the 
 * resource if needed
 */
class JsonParametersParser {
	
	public static function parseResourceForParametersValidationRoules( $resource ) {
		$par_rules = array();
		$par_filters = array();
		foreach ($resource->request->parameters as $key) {
			$par_rules[$key->name]   = $key->validation;
			$par_filters[$key->name] = 'trim';
		}
		return array( 'rules' => $par_rules, 'filters' => $par_filters );
	}
	
}
