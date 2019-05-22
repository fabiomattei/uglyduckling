<?php 

namespace Firststep\Common\Json\Checkers\Form;

use  Firststep\Common\Json\Checkers\BasicJsonChecker;

/**
 * 
 */
class FormV1JsonChecker extends BasicJsonChecker {

	function isResourceBlockWellStructured() : bool {
        $querysql = $this->resource->get->query->sql;
        $formfields = $this->resource->get->form->fields;

        foreach ( $formfields as $field ) {
            if ( isset( $field->sqlfield ) ) {
                if ( !$this->isFieldInQuery( $field->sqlfield, $querysql ) ) {
                    $this->errors[] = "Error for form field " . $field->name . " his sqlfield " . $field->sqlfield . " is not in query " . $querysql;
                    return false;
                }
            }
        }

	    return true;
    }

    /**
     * Return an array containing all actions defined in this resource
     *
     * @return array
     */
    public function getActionsDefinedInResource(): array {
	    $actions = $this->resource->get->form->topactions ?? array();
		if ( isset( $this->resource->post->redirect ) ) $actions[] = $this->resource->post->redirect;
	    // $actions = array_merge( $actions, $this->resource->post->redirect ?? array() );
        return $actions;
    }

    public function isFieldInQuery( $field, $query ): bool {
        return $this->isStringBetween( $field, $query, 'SELECT', 'FROM' );
    }

    /**
     * Check if a given $word is between the words $start and $end in a $string
     * This is useful in order to check is in a particular query
     * 
     * Ex.
     * $word = "name", $string = "SELECT name, address FROM People;", $start = "SELECT", $end = "FROM"
     * will return true because the word name is in the string between the words START and END
     *
     * @param string $word
     * @param string $string
     * @param string $start
     * @param string $end
     * @return bool
     */
    function isStringBetween( $word, $string, $start, $end ): bool {
        $string = ' ' . $string;
        $ini = strpos( $string, $start );
        if ($ini == 0) return false;
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        $string_in_the_middle = substr($string, $ini, $len);
        if ( strpos( $string_in_the_middle, $word ) !== false ) {
            return true;
        } else {
            return false;
        }
    }

}
