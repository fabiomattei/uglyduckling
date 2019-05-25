<?php 

namespace Firststep\Common\Json\Checkers\Chartjs;

use  Firststep\Common\Json\Checkers\BasicJsonChecker;
use Firststep\Common\Utils\StringUtils;

/**
 * 
 */
class ChartjsV1JsonChecker extends BasicJsonChecker {

	function isResourceBlockWellStructured() : bool {
        $out = true;
        $getParameters = $this->resource->get->request->parameters ?? array();
        $querysql = $this->resource->get->query->sql ?? '';
        $querySqlParameters = $this->resource->get->query->parameters ?? array();
        $actionOnClick = $this->resource->get->actiononclick ?? new \stdClass;

        // check if all query sql parameters are passed in the get parameters array
        foreach ($querySqlParameters as $sqlRequiredPar) {
            if (!array_filter($getParameters, function ($parToCheck) use ($sqlRequiredPar) {
                return $parToCheck->name === $sqlRequiredPar->getparameter;
            })) {
                $this->errors[] = 'Error for form, SQL parameter ' . $sqlRequiredPar->getparameter . ' it is not part of the get parameters array';
                $out = false;
            }
        }

        // check if all table actions fields are contained in the query selected fields
        if ( isset( $actionOnClick ) AND isset( $actionOnClick->parameters ) ) {
            foreach ( $actionOnClick->parameters as $par ) {
                if ( isset( $par->sqlfield ) ) {
                    if ( !$this->isFieldInQuery( $par->sqlfield, $querysql ) ) {
                        $this->errors[] = "Error for table action " . $par->name . " its sqlfield " . $par->sqlfield . " is not in query " . $querysql;
                        return false;
                    }
                }
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
    	$actions = array();
    	if ( isset($this->resource->get->actiononclick) ) {
    		$actions[] = $this->resource->get->actiononclick;
    	}
        return $actions;
    }

    public function isFieldInQuery( string $field = '', string $query = '' ): bool {
        return StringUtils::isFieldInSqlSelectCaseUnsensitive( $field, $query );
    }

}
