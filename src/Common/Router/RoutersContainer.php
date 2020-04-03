<?php

/**
 * Created Fabio Mattei
 * Date: 2019-10-13
 * Time: 15:20
 */

namespace Fabiom\UglyDuckling\Common\Router;

use Fabiom\UglyDuckling\Controllers\Community\Login;

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
	 * Sarch all contained routers in order to get the right contronller
	 */
	function getController( string $action ) {
        foreach ( $this->routers as $router ) {
            if ( $router->isActionSupported( $action ) ) return $router->getController( $action );
        }

        return new Login; // this is the defacto custom router
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
	
}
