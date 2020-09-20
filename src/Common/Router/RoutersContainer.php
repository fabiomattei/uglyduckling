<?php

/**
 * Created Fabio Mattei
 * Date: 2019-10-13
 * Time: 15:20
 */

namespace Fabiom\UglyDuckling\Common\Router;

use Fabiom\UglyDuckling\Common\Json\JsonLoader;
use Fabiom\UglyDuckling\Common\Status\PageStatus;

/**
 * Class RoutersContainer
 * @package Fabiom\UglyDuckling\Common\Router
 *
 * This class is a container of routers.
 * It is useful in order to find the right controller once a request (GET or POST) is made.
 * In order to do that it requires the existence of one or more routers.
 *
 * Once a request is made it checks all routers saved in his internal list and return
 * the right Controller class.
 *
 * If no controller is found return the default controller: Login
 */
class RoutersContainer {

	/**
	 * Array of routers
	 */
    private $routers;
    private /* string */ $defaultController;

    /**
     * JsonTemplateFactoriesContainer constructor.
	 * string $basepath app base path like www.myapp.com/myappfolder
     */
    public function __construct( $basepath ) {
        $this->routers = array();
        $this->basepath = $basepath;
    }

	/**
	 * Sarch all contained routers in order to get the right router
	 */
    public function getRouter( $resource ) {
        foreach ( $this->routers as $router ) {
            if ( $router->supports( $resource ) ) return $router;
        }
    }
	
	/**
	 * Set the default controller when no controller has been requested by the user
	 * it usually happens when user is at his first connection to the application
	 */
	public function setDefaultController( $controller ) {
		$this->defaultController = $controller;
	}
	
	/**
	 * Search all contained routers in order to get the right controller
	 */
	function getController( string $action ) {
        foreach ( $this->routers as $router ) {
            if ( $router->isActionSupported( $action ) ) return $router->getController( $action );
        }

        return $this->defaultController;
	}
	
    /**
     * Add a RouterBase object to the container
     * @param $router
     */
    public function addRouter( $router ) {
        $this->routers[] = $router;
    }
	
	/**
	 * Return a string containing the basepath of the application
	 */
    public function getInfo() : string {
        return '[Router] BasePath: '.$this->basepath;
    }

    /**
     * @param mixed $json_action
     * @param JsonLoader $jsonloader
     * @param PageStatus $pageStatus
     * @return mixed
     *
     * Example of a json action:
     *
     * {
     *   "type": "link",
     *   "label": "Info",
     *   "resource": "myinfopanel",
     *   "tooltip": "My tool tip text",
     *   "onclick": "My on click text",
     *   "buttoncolor": "green",
     *   "outline": false,
     *   "parameters":[
     *     {"name": "id", "sqlfield": "id"},
     *     {"name": "secondid", "constantparameter": "3"},
     *     {"name": "thirdid", "getparameter": "mygetparameter"}
     *   ]
     * }
     *
     * Check out: http://www.uddocs.com/docs/actions
     */
    function make_resource_url( $json_action, JsonLoader $jsonloader, PageStatus $pageStatus ) {
        $resource = $json_action->resource;
        $url_parameters = 'res='.$resource.'&';
        if ( isset( $json_action->parameters ) AND is_array($json_action->parameters) ) {
            foreach ($json_action->parameters as $par) {
                $url_parameters .= $par->name.'='.$pageStatus->getValue($par).'&';
            }
            $url_parameters = rtrim($url_parameters, '&');
        }

        $action = $jsonloader->getActionRelatedToResource( $resource );

        switch ( $action ) {
            case 'officeentitydashboard':
                return $this->makeRelativeUrl( ResourceRouter::ROUTE_OFFICE_ENTITY_DASHBOARD, $url_parameters );
                break;
            case 'officeentitytransaction':
                return $this->makeRelativeUrl( ResourceRouter::ROUTE_OFFICE_ENTITY_LOGIC, $url_parameters );
                break;

            default:
                return $this->makeRelativeUrl( ResourceRouter::ROUTE_OFFICE_ENTITY_DASHBOARD, $url_parameters );
                break;
        }
    }

    /**
     * It creates a URL appending the content of variable $_SESSION['office'] to BASEPATH
     * The URL created is relative and not absolute
     *
     * Result is: BASEPATH . $_SESSION['office'] . $final_part
     *
     * @param        string     Action
     * @param        string     Parameters: string containing all parameters separated by '/'
     * @param        string     Extension:  .html by default
     *
     * @return       string     The url well formed
     */
    function makeRelativeUrl( $action = '', $parameters = '', $extension = '.html' ) {
        if ( $action == '' ) {
            return '#';
        } else {
            return $action.$extension.( $parameters == '' ? '' : '?'.$parameters );
        }
    }
	
}
