<?php

namespace Fabiom\UglyDuckling\Controllers\Admin\Security;

use Fabiom\UglyDuckling\BusinessLogic\Ip\Daos\IpDao;
use Fabiom\UglyDuckling\Common\Blocks\ButtonForm;
use Fabiom\UglyDuckling\Common\Controllers\AdminController;
use Fabiom\UglyDuckling\Common\Router\AdminRouter;
use Fabiom\UglyDuckling\Templates\Blocks\Menus\AdminMenu;
use Fabiom\UglyDuckling\Templates\Blocks\Sidebars\AdminSidebar;
use Fabiom\UglyDuckling\Common\Blocks\StaticTable;
use Fabiom\UglyDuckling\Common\Blocks\Button;

/**
 *
 */
class BlockedIpList extends AdminController {

	private /* IpDao */ $ipDao;
		
	public function __construct() {
		$this->ipDao = new IpDao;		
	}
			
	/**
	 * @throws GeneralException
	 */
	public function getRequest() {
		$this->ipDao->setDBH( $this->pageStatus->getDbconnection()->getDBH() );
		$this->ipDao->setLogger( $this->applicationBuilder->getLogger() );
		$ips = $this->ipDao->getAll();
		
		$this->title = $this->applicationBuilder->getSetup()->getAppNameForPageTitle() . ' :: Admin Documents list';

		$table = new StaticTable;
		$table->setHtmlTemplateLoader( $this->applicationBuilder->getHtmlTemplateLoader() );
		$table->setTitle('Blocked IP\'s list');

		$table->addTHead();
		$table->addRow();
		$table->addHeadLineColumn('IP');
		$table->addHeadLineColumn('Failed attempts');
		$table->addHeadLineColumn('Time to remove');
		$table->addHeadLineColumn('Updated');
		$table->addHeadLineColumn('Created');
		$table->addHeadLineColumn(''); // adding one more for actions
		$table->closeRow();
		$table->closeTHead();

		$table->addTBody();
		foreach ( $ips as $ip ) {
			$table->addRow();
			$table->addColumn($ip->ip_ipaddress);
			$table->addColumn($ip->ip_failed_attepts);
			$table->addColumn($ip->ip_time_to_remove);
			$table->addColumn($ip->ip_updated);
			$table->addColumn($ip->ip_created);
			$table->addUnfilteredColumn(ButtonForm::get($this->applicationBuilder->getRouterContainer()->makeRelativeUrl( AdminRouter::ROUTE_ADMIN_SECURITY_BLOCKED_IP_DELETE ), 'Del', $_SESSION['csrftoken'], array('biid' => $ip->ip_id), Button::COLOR_RED.' '.Button::SMALL ));
			$table->closeRow();
		}
		$table->closeTBody();

		$this->menucontainer    = array( new AdminMenu( $this->applicationBuilder->getSetup()->getAppNameForPageTitle(), AdminRouter::ROUTE_ADMIN_GROUP_LIST ) );
		$this->leftcontainer    = array( new AdminSidebar( $this->applicationBuilder->getSetup()->getAppNameForPageTitle(), AdminRouter::ROUTE_ADMIN_GROUP_LIST, $this->applicationBuilder->getRouterContainer() ) );
		$this->centralcontainer = array( $table );

		$this->templateFile = $this->applicationBuilder->getSetup()->getPrivateTemplateWithSidebarFileName();
	}

}
