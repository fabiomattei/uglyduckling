<?php

namespace Fabiom\UglyDuckling\Common\Database;

class QuerySet {

	private $results = array();
    public $values;

	/**
	 * Check if a value has been set using isset
	 *
	 * @param $label
	 * @return bool
	 */
	public function isResultSet( $label ) {
		return isset($this->values[$label]);
	}

	/**
	 * Set a value
	 *
	 * @param $label
	 * @param $value
	 */
	public function setResult( $label, $value ) {
		$this->values[$label] = $value;
	}

	/**
	 * Set a value without to specify a key
	 *
	 * @param $value
	 */
	public function setResultNoKey( $value ) {
		$this->values[] = $value;
	}

	/**
	 * Return a returned value
	 *
	 * @param $label
	 * @return mixed
	 */
	public function getResult( $label ) {
		return $this->values[$label];
	}

	/**
	 * Return a pointer to a Returned Value
	 * Used for bindpar in PDO parameters
	 *
	 * @param $label
	 * @return mixed
	 */
	public function &getPointerToResult( $label ) {
		return $this->values[$label];
	}

}


