<?php 

namespace Firststep\Common\Json\Checkers\Info;

use  Firststep\Common\Json\Checkers\BasicJsonChecker;

/**
 * 
 */
class InfoV1JsonChecker extends BasicJsonChecker {

	function isResourceBlockWellStructured() : bool {
        $out = true;
        $getParameters = $this->resource->get->request->parameters ?? array();
        $querysql = $this->resource->get->query->sql ?? '';
        $querySqlParameters = $this->resource->get->query->parameters ?? array();
        $infoFields = $this->resource->get->info->fields ?? array();

        // check if all form fields are contained in the query selected fields
        // it performs this check only if the query has been set
        if ( $querysql != '' ) {
            foreach ( $infoFields as $field ) {
                if ( isset( $field->sqlfield ) AND isset( $querysql ) ) {
                    if ( !$this->isFieldInQuery( $field->sqlfield, $querysql ) ) {
                        $this->errors[] = 'Error for form field ' . $field->name . ' its sqlfield ' . $field->sqlfield . ' is not in query ' . $querysql;
                        $out = false;
                    }
                }
            }    
        }

        // check if all query sql parameters are passed in the get parameters array
        foreach ($querySqlParameters as $sqlRequiredPar) {
            if (!array_filter($getParameters, function ($parToCheck) use ($sqlRequiredPar) {
                echo "$parToCheck->name === $sqlRequiredPar->getparameter<br>";
                return $parToCheck->name === $sqlRequiredPar->getparameter;
            })) {
                $this->errors[] = 'Error for form, SQL parameter ' . $sqlRequiredPar->getparameter . ' it is not part of the get parameters array';
                $out = false;
            }
        }
        
        return $out;
    }

    /**
     * Return an array containing all actions defined in this resource
     *
     * @return array
     */
    public function getActionsDefinedInResource(): array {
	    $actions = $this->resource->get->info->topactions ?? array();
        return $actions;
    }

}
