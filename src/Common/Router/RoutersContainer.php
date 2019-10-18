<?php

/**
 * Created Fabio Mattei
 * Date: 2019-10-13
 * Time: 15:20
 */

namespace Fabiom\UglyDuckling\Common\Router;

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
	
}
