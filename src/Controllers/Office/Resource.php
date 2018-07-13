<?php

namespace Firststep\Controllers\Office;

use Firststep\Common\Controllers\Controller;

/**
 * 
 */
class Resource extends Controller {
	
	public function getRequest() {
		$this->jsonloader->loadIndex();
		$resource = $this->jsonloader->loadResource( $resourcekey );
		
		
		/*
		$this->title                  = $this->setup->getAppNameForPageTitle() . ' :: Admin dashboard';
		$this->menucontainer          = array( new AdminMenu( $this->setup->getAppNameForPageTitle(), 'admindashboard' ) );
		$this->leftcontainer          = array( new AdminSidebar( $this->setup->getAppNameForPageTitle(), 'admindashboard' ) );
		$this->centralcontainer       = array( new LineGraph );
		$this->secondcentralcontainer = array( new StaticTable );
		*/
	}

}
