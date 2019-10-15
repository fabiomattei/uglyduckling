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
    function isSupportingAction( string $action ) {
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

    public function getInfo() : string {
        return '[Router] BasePath: '.$this->basepath;
    }

}