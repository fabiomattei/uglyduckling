<?php

namespace Firststep\Controllers\Office\Manager;

use Firststep\Common\Controllers\Controller;
use Firststep\Templates\Blocks\Menus\GateMenu;
use Firststep\Templates\Blocks\Sidebars\GateSidebar;
use Firststep\Templates\Blocks\Graphs\LineGraph;
use Firststep\Common\Blocks\StaticTable;
use Firststep\Common\Blocks\BaseInfo;
use Firststep\Common\Blocks\Button;
use Firststep\Common\Router\Router;

/**
 * User: fabio
 * Date: 16/08/2018
 * Time: 12:02
 */
class Gate extends Controller {
	
	public function getRequest() {
		$info = new BaseInfo;
		$info->setTitle( 'Links: ' );
		$info->addParagraph( 'Table: '.Button::get($this->router->make_url( Router::ROUTE_OFFICE_ENTITY_TABLE, 'res=requesttable' ), 'Table', Button::COLOR_GRAY.' '.Button::SMALL ), '');
		$info->addParagraph( 'Form: '.Button::get($this->router->make_url( Router::ROUTE_OFFICE_ENTITY_FORM, 'res=formrequestv1' ), 'Form', Button::COLOR_GRAY.' '.Button::SMALL ), '');
		
		$this->title                  = $this->setup->getAppNameForPageTitle() . ' :: Manager dashboard';
		$this->menucontainer          = array( new GateMenu( $this->setup->getAppNameForPageTitle(), 'admindashboard' ) );
		$this->leftcontainer          = array( new GateSidebar( $this->setup->getAppNameForPageTitle(), 'admindashboard' ) );
		$this->centralcontainer       = array( new LineGraph, $info );
		$this->secondcentralcontainer = array( new StaticTable );
	}
	
	public function postRequest() {
		# code...
	}

}
