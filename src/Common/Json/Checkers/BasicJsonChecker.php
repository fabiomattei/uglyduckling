<?php 

namespace Fabiom\UglyDuckling\Common\Json\Checkers;

use Fabiom\UglyDuckling\Common\Json\Checkers\Chartjs\ChartjsV1JsonChecker;
use Fabiom\UglyDuckling\Common\Json\Checkers\Dashboard\DashboardV1JsonChecker;
use Fabiom\UglyDuckling\Common\Json\Checkers\Form\FormV1JsonChecker;
use Fabiom\UglyDuckling\Common\Json\Checkers\Info\InfoV1JsonChecker;
use Fabiom\UglyDuckling\Common\Json\Checkers\Group\GroupV1JsonChecker;
use Fabiom\UglyDuckling\Common\Json\Checkers\TabbedPage\TabbedPageV1JsonChecker;
use Fabiom\UglyDuckling\Common\Json\Checkers\Table\TableV1JsonChecker;
use Fabiom\UglyDuckling\Common\Json\Checkers\Transaction\TransactionV1JsonChecker;

/**
 * Basic class for json files checkers
 * Contains the factory to generate the right json checker based on the json resource we need to check
 * Contains the basic structure
 */
class BasicJsonChecker {

	protected $resource;
	protected $errors = array();
	
	function __construct( $resource ) {
        $this->resource = $resource;
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
	function isActionPresent( $action ): bool {
		$out = false;
        foreach ( $this->getActionsDefinedInResource() as $definedAction ) {
            if ( $definedAction->resource === $action ) {
                $out = true;
            }
			// echo $definedAction->resource . '===' . $action . '  -  ';
        }
		// echo $out ? 'true' : 'false';
        return $out;
    }

    /**
     * @param $action     action name
     * @param $parameters $this->resource->get->request->parameters
     * @return bool
     */
	function isActionPresentAndWellStructured( $action, $parameters ): bool {
        foreach ( $this->getActionsDefinedInResource() as $definedAction ) {
            if ( $definedAction->resource === $action ) {
                if ( is_array($parameters) ) {
                    foreach ( $parameters as $requiredParameter ) {
                        if ( isset($definedAction->parameters) ) {
                            if (!array_filter($definedAction->parameters, function ($parToCheck) use ($requiredParameter) {
                                return $parToCheck->name === $requiredParameter->name;
                            })) {
                                $this->errors[] = "Error for action " . $action;
                                return false;
                            }
                        }
                    }
                }
            }
        }
        return true;
    }

    /**
     * Check if the structure of the internally defined block is well done
     *
     * @return bool
     */
    function isResourceBlockWellStructured() : bool {
        $this->errors[] = 'Undefined checker';
        return false;
    }

    /**
     * Return an array containing all actions defined in this resource
     *
     * @return array
     */
	public function getActionsDefinedInResource(): array {
		return array();
	}

	public function getErrorsString(): string {
	    $errorString = '';
        foreach ( $this->errors as $error) {
            $errorString .= $error . '<br />';
        }
        return $errorString;
    }

	public static function basicJsonCheckerFactory( $resource ): BasicJsonChecker {
        if ( $resource->metadata->type === "chartjs" )     return new ChartjsV1JsonChecker( $resource );
        if ( $resource->metadata->type === "dashboard" )   return new DashboardV1JsonChecker( $resource );
        if ( $resource->metadata->type === "form" )        return new FormV1JsonChecker( $resource );
        if ( $resource->metadata->type === "group" )       return new GroupV1JsonChecker( $resource );
        if ( $resource->metadata->type === "info" )        return new InfoV1JsonChecker( $resource );
        if ( $resource->metadata->type === "table" OR $resource->metadata->type === "datatable" )       return new TableV1JsonChecker( $resource );
        if ( $resource->metadata->type === "tabbedpage" )  return new TabbedPageV1JsonChecker( $resource );
        if ( $resource->metadata->type === "transaction" ) return new TransactionV1JsonChecker( $resource );
        return new BasicJsonChecker( $resource );
	}

}
