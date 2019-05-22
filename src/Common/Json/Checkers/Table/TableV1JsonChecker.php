<?php

namespace Firststep\Common\Json\Checkers\Table;

use  Firststep\Common\Json\Checkers\BasicJsonChecker;
use Firststep\Common\Utils\StringUtils;

/**
 *
 */
class TableV1JsonChecker extends BasicJsonChecker {

    function isResourceBlockWellStructured() : bool {
        $querysql = $this->resource->get->query->sql;
        $tablefields = $this->resource->get->table->fields;
        $tableactions = $this->resource->get->table->actions;

        foreach ( $tablefields as $field ) {
            if ( isset( $field->sqlfield ) ) {
                if ( !$this->isFieldInQuery( $field->sqlfield, $querysql ) ) {
                    $this->errors[] = "Error for table field " . $field->name . " his sqlfield " . $field->sqlfield . " is not in query " . $querysql;
                    return false;
                }
            }
        }

        foreach ( $tableactions as $action ) {
            if ( isset( $action->parameters ) ) {
                foreach ( $action->parameters as $par ) {
                    if ( isset( $par->sqlfield ) ) {
                        if ( !$this->isFieldInQuery( $par->sqlfield, $querysql ) ) {
                            $this->errors[] = "Error for table action " . $par->name . " his sqlfield " . $par->sqlfield . " is not in query " . $querysql;
                            return false;
                        }
                    }
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
        $actions = array_merge( $actions, $this->resource->get->table->actions ?? array() );
        return $actions;
    }

    public function isFieldInQuery( $field, $query ): bool {
        return StringUtils::isStringBetweenCaseUnsensitive( $field, $query, 'SELECT', 'FROM' );
    }

}
