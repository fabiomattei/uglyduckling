<?php

namespace Firststep\Common\Router;

use Firststep\Controllers\Office\Inbox;
use Firststep\Controllers\Community\Login;
use Firststep\Controllers\Admin\Dashboard\AdminDashboard;

class Router {
	
	const ROUTE_OFFICE_INBOX = 'officeinbox';
	const ROUTE_COMMUNITY_LOGIN = 'communitylogin';
	const ROUTE_ADMIN_DASHBOARD = 'admindashboard';
	
	public function __construct( $basepath ) {
		$this->basepath = $basepath;
	}

    function getController( string $action ) {
        switch ( $action ) {
            case ROUTE_OFFICE_INBOX: return new Inbox;
			case ROUTE_COMMUNITY_LOGIN: return new Login;
			case ROUTE_ADMIN_DASHBOARD: return new AdminDashboard;
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
		if ( $action == '' ) {
			return $this->basepath;
		} else {
	        return $this->basepath.$action.( $parameters == '' ? '' : '/'.$parameters ).$extension;
	    }
	}

}
