<?php

namespace Firststep\Controllers\Office\Manager;

use Firststep\Common\Controllers\Controller;

use Firststep\Templates\Blocks\Menus\GateMenu;
use Firststep\Templates\Blocks\Sidebars\GateSidebar;
use Firststep\Templates\Blocks\Graphs\LineGraph;
use Firststep\Common\Blocks\StaticTable;

/**
 * 
 */
class Gate extends Controller {
	
	public function getRequest() {
		$this->title                  = $this->setup->getAppNameForPageTitle() . ' :: Manager dashboard';
		$this->menucontainer          = array( new GateMenu( $this->setup->getAppNameForPageTitle(), 'admindashboard' ) );
		$this->leftcontainer          = array( new GateSidebar( $this->setup->getAppNameForPageTitle(), 'admindashboard' ) );
		$this->centralcontainer       = array( new LineGraph );
		$this->secondcentralcontainer = array( new StaticTable );
	}
	
	public function postRequest() {
		# code...
	}

}
