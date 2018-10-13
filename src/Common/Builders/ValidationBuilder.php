<?php

/**
 * User: fabio
 * Date: 04/08/2018
 * Time: 20:59
 */

namespace Firststep\Common\Builders;

class ValidationBuilder {
	
	public function getValidationRoules( $parameters ) {
		$rules = array();
		foreach ($parameters as $par) {
			$rules[$par->name] = $par->validation;
		}
		return $rules;
	}
	
	public function getValidationFilters( $parameters ) {
		$filters = array();
		foreach ($parameters as $par) {
			$filters[$par->name] = 'trim';
		}
		return $filters;
	}

	public function postValidationRoules( $fields ) {
		$rules = array();
		if( is_array($fields) ) {
			foreach ( $fields as $field ) {
				$rules[$field->name] = $field->validation;
			}
		}
		return $rules;
	}
	
	public function postValidationFilters( $fields ) {
		$filters = array();
		if( is_array($fields) ) {
			foreach ( $fields as $field ) {
				$filters[$field->name] = 'trim';
			}
		}
		return $filters;
	}

}
