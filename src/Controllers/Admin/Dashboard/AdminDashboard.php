<?php

namespace Firststep\Controllers\Admin\Dashboard;

use Firststep\Common\Controllers\Controller;
use Firststep\Templates\Blocks\Menus\AdminMenu;
use Firststep\Templates\Blocks\Login\LoginForm;

/**
 * 
 */
class AdminDashboard extends Controller {
	
	function __construct() {
		// empty as you see
	}

	public function getRequest() {
		$this->title            = $this->setup->getAppNameForPageTitle() . ' :: Admin dashboard';
		$this->menucontainer    = array( new AdminMenu( $this->setup->getAppNameForPageTitle(), 'admindashboard' ) );
		$this->centralcontainer = array();
		echo "ci sono";
		// $this->templateFile     = 'login';
	}

}
