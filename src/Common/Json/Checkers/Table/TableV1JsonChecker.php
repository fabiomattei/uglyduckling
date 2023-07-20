<?php

namespace Fabiom\UglyDuckling\Common\Json\Checkers\Table;

use Fabiom\UglyDuckling\Common\Json\Checkers\BasicJsonChecker;
use Fabiom\UglyDuckling\Common\Utils\StringUtils;

/**
 *
 */
class TableV1JsonChecker extends BasicJsonChecker {

    function isResourceBlockWellStructured() : bool {
        $querysql = $this->resource->get->query->sql ?? '';
        $querySqlParameters = $this->resource->get->query->parameters ?? array();
        $getParameters = $this->resource->get->request->parameters ?? array();
        $tableFields = $this->resource->get->table->fields;
        $tableActions = $this->resource->get->table->actions;
        
        // check if all table fields are contained in the query selected fields
        foreach ( $tableFields as $field ) {
            if ( isset( $field->sqlfield ) ) {
                if ( !$this->isFieldInQuery( $field->sqlfield, $querysql ) ) {
                    $this->errors[] = "Error for table field " . $field->name . " its sqlfield " . $field->sqlfield . " is not in query " . $querysql;
                    return false;
                }
            }
        }

        // check if all table actions fields are contained in the query selected fields
        foreach ( $tableActions as $action ) {
            if ( isset( $action->parameters ) ) {
                foreach ( $action->parameters as $par ) {
                    if ( isset( $par->sqlfield ) ) {
                        if ( !$this->isFieldInQuery( $par->sqlfield, $querysql ) ) {
                            $this->errors[] = "Error for table action " . $par->name . " its sqlfield " . $par->sqlfield . " is not in query " . $querysql;
                            return false;
                        }
                    }
                }
            }
        }

        // check if all query sql parameters are passed in the get parameters array
        /*
        foreach ($querySqlParameters as $sqlRequiredPar) {
            if (!array_filter($getParameters, function ($parToCheck) use ($sqlRequiredPar) {
                return property_exists($sqlRequiredPar, 'getparameter') AND $parToCheck->name === $sqlRequiredPar->getparameter;
            })) {
                if ( !property_exists($sqlRequiredPar, 'getparameter') ) {
                    $this->errors[] = 'Error for table, GET parameter ' . $sqlRequiredPar->getparameter . ' it is not defined';
                }
                $this->errors[] = 'Error for table, SQL parameter ' . $sqlRequiredPar->getparameter . ' it is not part of the get parameters array';
                return false;
            }
        }
        */

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
        return StringUtils::isFieldInSqlSelectCaseUnsensitive( $field ?? '', $query ?? '' );
    }

}
