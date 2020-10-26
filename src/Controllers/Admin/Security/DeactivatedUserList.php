<?php

namespace Fabiom\UglyDuckling\Controllers\Admin\Security;

use Fabiom\UglyDuckling\BusinessLogic\Ip\Daos\DeactivatedUserDao;
use Fabiom\UglyDuckling\Common\Controllers\AdminController;
use Fabiom\UglyDuckling\Common\Router\AdminRouter;
use Fabiom\UglyDuckling\Templates\Blocks\Menus\AdminMenu;
use Fabiom\UglyDuckling\Templates\Blocks\Sidebars\AdminSidebar;
use Fabiom\UglyDuckling\Common\Blocks\StaticTable;
use Fabiom\UglyDuckling\Common\Blocks\Button;

/**
 *
 */
class DeactivatedUserList extends AdminController {
	
	private /* DeactivatedUserDao */ $deactivatedUserDao;
	
	public function __construct() {
		$this->deactivatedUserDao = new DeactivatedUserDao;
	}

	/**
	 * @throws GeneralException
	 */
	public function getRequest() {
		$this->title = $this->applicationBuilder->getSetup()->getAppNameForPageTitle() . ' :: Admin Documents list';

		$table = new StaticTable;
		$table->setHtmlTemplateLoader( $this->applicationBuilder->getHtmlTemplateLoader() );
		$table->setTitle('Deactivated users list');

		$table->addTHead();
		$table->addRow();
		$table->addHeadLineColumn('Username');
		$table->addHeadLineColumn('Created');
		$table->addHeadLineColumn(''); // adding one more for actions
		$table->closeRow();
		$table->closeTHead();
		
		$this->deactivatedUserDao->setDBH( $this->pageStatus->getDbconnection()->getDBH() );
		$users = $this->deactivatedUserDao->getAll();

		$table->addTBody();
		foreach ( $users as $user ) {
			$table->addRow();
			$table->addColumn($user->du_username);
			$table->addColumn($user->du_created);
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
