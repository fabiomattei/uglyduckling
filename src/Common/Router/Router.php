<?php

namespace Firststep\Common\Router;

use Firststep\Controllers\Office\Inbox;
use Firststep\Controllers\Community\Login;

class Router {
	
	const ROUTE_OFFICE_INBOX = 'officeinbox';
	const ROUTE_COMMUNITY_LOGIN = 'communitylogin';
	
	public function __construct( $basepath ) {
		$this->basepath = $basepath;
	}

    function getController( string $argument ) {
        switch ( $argument ) {
            case ROUTE_OFFICE_INBOX: return new Inbox;
			case ROUTE_COMMUNITY_LOGIN: return new Login;
            default: return new Login;
        }
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
		if ( $chapter == 'main' AND $action == '' ) {
			return $this->basepath;
		}
		if ( $chapter != 'main' AND $action == '' ) {
			return $this->basepath.'/index.html';
		}
	    if ( $chapter == 'main' ) {
	        return $this->basepath.$action.( $parameters == '' ? '' : '/'.$parameters ).$extension;
	    } else {
	        return $this->basepath.$chapter.'/'.$action.( $parameters == '' ? '' : '/'.$parameters ).$extension;
	    }
	}

}
