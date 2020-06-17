<?php

/**
 * Created Fabio Mattei
 * Date: 2019-10-13
 * Time: 15:20
 */

namespace Fabiom\UglyDuckling\Common\Router;

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
	 * Sarch all contained routers in order to get the right contronller
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
     * It creates a URL appending the content of variable $_SESSION['office'] to BASEPATH
     * The URL created is absolute
     *
     * Result is: BASEPATH . $_SESSION['office'] . $final_part
     *
     * @param        string     Action
     * @param        string     Parameters: string containing all parameters separated by '/'
     * @param        string     Extension:  .html by default
     *
     * @return       string     The url well formed
     */
    function make_url( $action = '', $parameters = '', $extension = '.html' ) {
        if ( $action == '' ) {
            return $this->basepath;
        } else {
            return $this->basepath.$action.$extension.( $parameters == '' ? '' : '?'.$parameters );
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
            return '';
        } else {
            return $action.$extension.( $parameters == '' ? '' : '?'.$parameters );
        }
    }
	
}
