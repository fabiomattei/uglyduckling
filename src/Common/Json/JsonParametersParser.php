<?php

namespace Fabiom\UglyDuckling\Common\Json;

/**
 * JsonLoader makes an index of all available resources and load the 
 * resource if needed
 */
class JsonParametersParser {

    /**
     * Thake a resource json parsed structure and takes out the parameters from
     * the structure.
     *
     * Return an array containin two arrays:
     * array['rules']   contains the rules taken from json parsed structure
     * array['filters'] contains the filters taken from json parsed structure
     * @param $resource
     * @return array
     */
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
