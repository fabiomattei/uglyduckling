<?php 

namespace Firststep\Common\Json\Checkers;

/**
 * 
 */
class BasicJsonChecker {

	protected $resource;
	
	function __construct(argument) {
		# code...
	}

	/**
     * @param mixed $resource
     */
    public function setResource( $resource ) {
        $this->resource = $resource;
    }

	/**
	 * Check if the group passed has access to the resource
	 */
	public function isGroupAllowedToAccess( string $groupslug ) {
		return ( isset( $this->resource->allowedgroups ) AND in_array( $groupslug, $this->resource->allowedgroups) );
	}

	public function getActionStructureToThisResource() {
		# code...
	}

	public function areActionsWellStructured( $action ) {
		# code...
	}

}
