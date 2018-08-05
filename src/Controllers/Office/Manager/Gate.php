<?php

namespace Firststep\Controllers\Office\Manager;

use Firststep\Common\Controllers\Controller;

/**
 * 
 */
class Gate extends Controller {
	
	public function getRequest() {
		$this->title                  = $this->setup->getAppNameForPageTitle() . ' :: Admin dashboard';
		$this->menucontainer          = array( new AdminMenu( $this->setup->getAppNameForPageTitle(), 'admindashboard' ) );
		$this->leftcontainer          = array( new AdminSidebar( $this->setup->getAppNameForPageTitle(), 'admindashboard' ) );
		$this->centralcontainer       = array( new LineGraph );
		$this->secondcentralcontainer = array( new StaticTable );
	}
	
	public function postRequest() {
		# code...
	}

}
