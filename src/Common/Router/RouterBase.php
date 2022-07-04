<?php

/**
 * Created Fabio Mattei
 * Date: 2019-10-13
 * Time: 17:14
 */

namespace Fabiom\UglyDuckling\Common\Router;


class RouterBase {

    public function __construct( $basepath ) {
        $this->basepath = $basepath;
    }

    /**
     * Overwrite this function
     *
     * @param string $controller
     */
    function isActionSupported( string $controllerSlug ) {
        return false;
    }

    /**
     * Overwrite this function
     *
     * @param string $action
     */
    function getController( string $controllerSlug ) {
		// overwrite this function
    }

}