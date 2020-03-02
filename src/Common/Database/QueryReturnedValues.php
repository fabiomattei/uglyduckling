<?php

namespace Fabiom\UglyDuckling\Common\Database;

/**
 * This class allows me to save returner values
 */
class QueryReturnedValues {

    private $values;

    public function isValueSet( $label ) {
        return isset($this->values[$label]);
    }

    public function setValue( $label, $value ) {
        $this->values[$label] = $value;
    }

    public function setValueNoKey( $value ) {
        $this->values[] = $value;
    }

    public function &getValue( $label ) {
        return $this->values[$label];
    }

}
