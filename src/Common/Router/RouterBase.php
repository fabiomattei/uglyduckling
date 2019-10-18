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
     * @param string $action
     */
    function isActionSupported( string $action ) {
        return false;
    }

    /**
     * Overwrite this function
     *
     * @param string $action
     */
    function getController( string $action ) {
		// overwrite this function
    }

}