<?php 

namespace Firststep\Common\Json\Checkers\Form;

use Firststep\Common\Json\Checkers\BasicJsonChecker;
use Firststep\Common\Utils\StringUtils;

/**
 * Make all checks for form entity version 1
 */
class FormV1JsonChecker extends BasicJsonChecker {

	function isResourceBlockWellStructured() : bool {
        $querysql = $this->resource->get->query->sql;
        $formfields = $this->resource->get->form->fields;

        foreach ( $formfields as $field ) {
            if ( isset( $field->sqlfield ) AND isset( $querysql ) ) {
                if ( !$this->isFieldInQuery( $field->sqlfield, $querysql ) ) {
                    $this->errors[] = 'Error for form field ' . $field->name . ' his sqlfield ' . $field->sqlfield . ' is not in query ' . $querysql;
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

    public function isFieldInQuery( string $field = '', string $query = '' ): bool {
        return StringUtils::isStringBetweenCaseUnsensitive( $field, $query, 'SELECT', 'FROM' );
    }

}
