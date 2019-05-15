<?php 

namespace Firststep\Common\Json\Checkers;

/**
 * 
 */
class BasicJsonChecker {

	protected $resource;
	protected $errors = array();
	
	function __construct() {
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
     *
     * @param string $groupslug
     * @return bool
     */
	public function isGroupAllowedToAccess( string $groupslug ): bool {
		return ( isset( $this->resource->allowedgroups ) AND in_array( $groupslug, $this->resource->allowedgroups) );
	}

    /**
     * @param $action     action name
     * @param $parameters $this->resource->get->request->parameters
     * @return bool
     */
	function isActionPresentAndWellStructured( $action, $parameters ): bool {
        foreach ($this->getActionsDefinedInResource() as $definedAction) {
            if ( $definedAction->action === $action ) {
                foreach ( $parameters as $requiredParameter ) {
                    if (!array_filter($definedAction->parameters, function($parToCheck) use ($requiredParameter) {
                        return $parToCheck->name === $requiredParameter->name;
                    })) {
                        $this->errors[] = "Error for action " . $action;
                        return false;
                    }
                }
            }
        }
        return true;
    }

    /**
     * Check if the strcture of the internally defined block is well done
     *
     * @return bool
     */
    function isResourceBlockWellStructured() : bool {
        return true;
    }

    /**
     * Return an array containing all actions defined in this resource
     *
     * @return array
     */
	public function getActionsDefinedInResource(): array {
		return array();
	}

}
