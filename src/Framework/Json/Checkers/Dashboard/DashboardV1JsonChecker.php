<?php 

namespace Fabiom\UglyDuckling\Framework\Json\Checkers\Dashboard;

use  Fabiom\UglyDuckling\Common\Json\Checkers\BasicJsonChecker;
use Fabiom\UglyDuckling\Common\Utils\StringUtils;

/**
 * 
 */
class DashboardV1JsonChecker extends BasicJsonChecker {

	function isResourceBlockWellStructured() : bool {
        $out = true;
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
        return StringUtils::isStringBetweenCaseUnsensitive( $field, $query, 'SELECT', 'FROM' );
    }

}
