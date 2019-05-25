<?php 

namespace Firststep\Common\Json\Checkers\TabbedPage;

use  Firststep\Common\Json\Checkers\BasicJsonChecker;
use Firststep\Common\Utils\StringUtils;

/**
 * 
 */
class TabbedPageV1JsonChecker extends BasicJsonChecker {

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
        return StringUtils::isFieldInSqlSelectCaseUnsensitive( $field, $query );
    }

}
