<?php

namespace Firststep\Controllers\Admin\Dashboard;

use Firststep\Common\Controllers\Controller;
use Firststep\Templates\Blocks\Menus\AdminMenu;
use Firststep\Templates\Blocks\Sidebars\AdminSidebar;
use Firststep\Templates\Blocks\Graphs\LineGraph;
use Firststep\Common\Blocks\StaticTable;
use Firststep\Common\Router\Router;

/**
 * 
 */
class AdminDashboard extends Controller {

	public function getRequest() {
		$this->title                  = $this->setup->getAppNameForPageTitle() . ' :: Admin dashboard';
		$this->menucontainer          = array( new AdminMenu( $this->setup->getAppNameForPageTitle(), Router::ROUTE_ADMIN_DASHBOARD ) );
		$this->leftcontainer          = array( new AdminSidebar( $this->setup->getAppNameForPageTitle(), Router::ROUTE_ADMIN_DASHBOARD, $this->router ) );
		$this->centralcontainer       = array();
		$this->secondcentralcontainer = array( new StaticTable );

		$this->templateFile = $this->setup->getPrivateTemplateWithSidebarFileName();
	}

}
