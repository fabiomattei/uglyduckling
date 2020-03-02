<?php

namespace Fabiom\UglyDuckling\Common\Database;

/**
 * This class allows me to save returner values
 */
class QueryReturnedValues {

    private $values = array();

    /**
     * Check if a value has been set using isset
     *
     * @param $label
     * @return bool
     */
    public function isValueSet( $label ) {
        return isset($this->values[$label]);
    }

    /**
     * Set a value
     *
     * @param $label
     * @param $value
     */
    public function setValue( $label, $value ) {
        $this->values[$label] = $value;
    }

    /**
     * Set a value without to specify a key
     *
     * @param $value
     */
    public function setValueNoKey( $value ) {
        $this->values[] = $value;
    }

    /**
     * Return a returned value
     *
     * @param $label
     * @return mixed
     */
    public function getValue( $label ) {
        return $this->values[$label];
    }

    /**
     * Return a pointer to a Returned Value
     * Used for bindpar in PDO parameters
     *
     * @param $label
     * @return mixed
     */
    public function &getPointerToValue( $label ) {
        return $this->values[$label];
    }

}
