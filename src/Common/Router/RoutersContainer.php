<?php

/**
 * Created Fabio Mattei
 * Date: 2019-10-13
 * Time: 15:20
 */

namespace Fabiom\UglyDuckling\Common\Router;

use Fabiom\UglyDuckling\Common\Controllers\Controller;
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
    private $routerContainers;
    private $defaultController;
    private string $basepath;

    /**
     * JsonTemplateFactoriesContainer constructor.
	 * string $basepath app base path like www.myapp.com/myappfolder
     */
    public function __construct( $basepath ) {
        $this->routerContainers = array();
        $this->basepath = $basepath;
    }

	/**
	 * Sarch all contained routers in order to get the right router
	 */
    public function getRouter( $controllerSlug ) {
        foreach ($this->routerContainers as $routerContainer ) {
            if ( $routerContainer->supports( $controllerSlug ) ) return $routerContainer;
        }
    }
	
	/**
	 * Set the default controller when no controller has been requested by the user
	 * it usually happens when user is at his first connection to the application
	 */
	public function setDefaultController( $defaultController ) {
		$this->defaultController = $defaultController;
	}

    /**
     * Set the default controller when no controller has been requested by the user
     * it usually happens when user is at his first connection to the application
     */
    public function getDefaultController() {
        return $this->defaultController;
    }
	
	/**
	 * Search all contained routers in order to get the right controller
	 */
	function getController( string $controllerSlug ) {
        foreach ($this->routerContainers as $router ) {
            if ( $router->isActionSupported( $controllerSlug ) ) return $router->getController( $controllerSlug );
        }

        return $this->defaultController;
	}
	
    /**
     * Add a RouterBase object to the container
     * @param $router
     */
    public function addRouter( $router ) {
        $this->routerContainers[] = $router;
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
     * I case I want to link a controller
     * {
     *   "type": "link",
     *   "label": "Info",
     *   "controller": "myinfopanel",
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
     * In case I want to link a URL
     * {
     *   "type": "link",
     *   "label": "Info",
     *   "url": "www.google.com",
     *   "tooltip": "My tool tip text",
     *   "onclick": "My on click text",
     *   "buttoncolor": "green",
     *   "outline": false
     * }
     *
     * Check out: http://www.uddocs.com/docs/actions
     */
    function make_resource_url( $json_action, PageStatus $pageStatus ) {
        if ( isset( $json_action->url ) ) {
            return $json_action->url;
        }

        if ( isset( $json_action->resource ) AND isset( $json_action->controller ) ) {
            $url_parameters = 'res=' . $json_action->resource . '&';
            if ( isset( $json_action->parameters ) AND is_array($json_action->parameters) ) {
                foreach ($json_action->parameters as $par) {
                    $url_parameters .= $par->name.'='.$pageStatus->getValue($par).'&';
                }
                $url_parameters = rtrim( $url_parameters, '&' );
            }
            return $this->makeRelativeUrl( $json_action->controller, $url_parameters );
        }

        if ( isset( $json_action->controller ) ) {
            $url_parameters = '';
            if ( isset( $json_action->parameters ) AND is_array($json_action->parameters) ) {
                foreach ($json_action->parameters as $par) {
                    $url_parameters .= $par->name.'='.$pageStatus->getValue($par).'&';
                }
                $url_parameters = rtrim($url_parameters, '&');
            }
            return $this->makeRelativeUrl( $json_action->controller, $url_parameters );
        }

        if ( isset( $json_action->resource ) ) {
            $resource = $json_action->resource;
            $url_parameters = 'res='.$resource.'&';
            if ( isset( $json_action->parameters ) AND is_array($json_action->parameters) ) {
                foreach ($json_action->parameters as $par) {
                    $url_parameters .= $par->name.'='.$pageStatus->getValue($par).'&';
                }
                $url_parameters = rtrim($url_parameters, '&');
            }
            return $this->makeRelativeUrl( ResourceRouter::ROUTE_OFFICE_ENTITY_DASHBOARD, $url_parameters );
        }

        // going to default controller
        if ( isset( $json_action->parameters ) AND is_array($json_action->parameters) ) {
            foreach ($json_action->parameters as $par) {
                $url_parameters .= $par->name.'='.$pageStatus->getValue($par).'&';
            }
            $url_parameters = rtrim($url_parameters, '&');
        }
        return $this->makeRelativeUrl( ResourceRouter::ROUTE_OFFICE_ENTITY_DASHBOARD, $url_parameters );

        // TODO Activate this in future
        //throw new \Exception('[UD Error] No action or controller or URL defined');
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
    function makeRelativeUrl( $controllerSlug = '', $parameters = '', $extension = '.html' ) {
        if ( $controllerSlug == '' ) {
            return '#';
        } else {
            return $controllerSlug.$extension.( $parameters == '' ? '' : '?'.$parameters );
        }
    }
	
}
