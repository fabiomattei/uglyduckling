<?php

/**
 * User: fabio
 * Date: 4/08/2018
 * Time: 20:59
 */

namespace Firststep\Common\Builders;

class ValidationBuilder {
	
	public function getValidationRoules( $parameters ) {
		$rules = array();
		$parnum = 0;
		foreach ($parameters as $par) {
			$rules[$parnum] = $par->validation;
			$parnum++;
		}
		return $rules;
	}
	
	public function getValidationFilters( $parameters ) {
		$filters = array();
		$parnum = 0;
		foreach ($parameters as $par) {
			$filters[$parnum] = 'trim';
			$parnum++;
		}
		return $filters;
	}
	
	public function postValidationRoules( $parameters ) {
		$rules = array();
		foreach ($parameters as $par) {
			$rules[$par->name] = $par->validation;
		}
		return $rules;
	}
	
	public function postValidationFilters( $parameters ) {
		$filters = array();
		foreach ($parameters as $par) {
			$filters[$par->name] = 'trim';
		}
		return $filters;
	}

}
