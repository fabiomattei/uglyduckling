<?php

namespace Fabiom\UglyDuckling\Controllers\Admin\Security;

use Fabiom\UglyDuckling\BusinessLogic\Ip\Daos\SecurityLogDao;
use Fabiom\UglyDuckling\Common\Controllers\AdminController;
use Fabiom\UglyDuckling\Common\Router\AdminRouter;
use Fabiom\UglyDuckling\Templates\Blocks\Menus\AdminMenu;
use Fabiom\UglyDuckling\Templates\Blocks\Sidebars\AdminSidebar;
use Fabiom\UglyDuckling\Common\Blocks\StaticTable;
use Fabiom\UglyDuckling\Common\Blocks\Button;

/**
 *
 */
class SecurityLogList extends AdminController {

	private /* SecurityLogDao */ $securityLogDao;
		
	public function __construct() {
		$this->securityLogDao = new SecurityLogDao;
	}
			
	/**
	 * @throws GeneralException
	 */
	public function getRequest() {
		$this->title = $this->applicationBuilder->getSetup()->getAppNameForPageTitle() . ' :: Admin Documents list';

		$table = new StaticTable;
		$table->setHtmlTemplateLoader( $this->applicationBuilder->getHtmlTemplateLoader() );
		$table->setTitle('Security logs');

		$table->addTHead();
		$table->addRow();
		$table->addHeadLineColumn('IP');
		$table->addHeadLineColumn('Username');
		$table->addHeadLineColumn('Password');
		$table->addHeadLineColumn('Description');
		$table->addHeadLineColumn('Created');
		$table->addHeadLineColumn(''); // adding one more for actions
		$table->closeRow();
		$table->closeTHead();
	
		$this->securityLogDao->setDBH( $this->pageStatus->getDbconnection()->getDBH() );
		$this->securityLogDao->setLogger( $this->applicationBuilder->getLogger() );
		
        $logs = $this->securityLogDao->getAll();
		
		$table->addTBody();
		foreach ( $logs as $log ) {
			$table->addRow();
			$table->addColumn($log->sl_ipaddress);
			$table->addColumn($log->sl_username);
			$table->addColumn($log->sl_password);
			$table->addColumn($log->sl_description);
			$table->addColumn($log->sl_created);
			$table->addUnfilteredColumn('');
			$table->closeRow();
		}
		$table->closeTBody();

		$this->menucontainer    = array( new AdminMenu( $this->applicationBuilder->getSetup()->getAppNameForPageTitle(), AdminRouter::ROUTE_ADMIN_GROUP_LIST ) );
		$this->leftcontainer    = array( new AdminSidebar( $this->applicationBuilder->getSetup()->getAppNameForPageTitle(), AdminRouter::ROUTE_ADMIN_GROUP_LIST, $this->applicationBuilder->getRouterContainer() ) );
		$this->centralcontainer = array( $table );

		$this->templateFile = $this->applicationBuilder->getSetup()->getPrivateTemplateWithSidebarFileName();
	}

}
