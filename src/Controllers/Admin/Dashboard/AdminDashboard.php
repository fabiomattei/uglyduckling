<?php

namespace Firststep\Controllers\Admin\Dashboard;

use Firststep\Common\Controllers\Controller;
use Firststep\Templates\Blocks\Menus\AdminMenu;
use Firststep\Templates\Blocks\Sidebars\AdminSidebar;
use Firststep\Templates\Blocks\Graphs\LineGraph;
use Firststep\Templates\Blocks\Tables\StaticTable;

/**
 * 
 */
class AdminDashboard extends Controller {
	
	function __construct() {
		// empty as you see
	}

	public function getRequest() {
		$this->title                  = $this->setup->getAppNameForPageTitle() . ' :: Admin dashboard';
		$this->menucontainer          = array( new AdminMenu( $this->setup->getAppNameForPageTitle(), 'admindashboard' ) );
		$this->leftcontainer          = array( new AdminSidebar( $this->setup->getAppNameForPageTitle(), 'admindashboard' ) );
		$this->centralcontainer       = array( new LineGraph );
		$this->secondcentralcontainer = array( new StaticTable );
	}

}
