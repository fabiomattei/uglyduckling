<?php

namespace Fabiom\UglyDuckling\Controllers\Admin\Dashboard;

use Fabiom\UglyDuckling\Common\Controllers\Controller;
use Fabiom\UglyDuckling\Common\Json\Checkers\BasicJsonChecker;
use Fabiom\UglyDuckling\Common\Json\Parameters\BasicParameterGetter;
use Fabiom\UglyDuckling\Templates\Blocks\Menus\AdminMenu;
use Fabiom\UglyDuckling\Templates\Blocks\Sidebars\AdminSidebar;
use Fabiom\UglyDuckling\Common\Blocks\StaticTable;
use Fabiom\UglyDuckling\Common\Router\Router;

/**
 * 
 */
class AdminDashboard extends Controller {

    /**
     * Overwrite parent showPage method in order to add the functionality of loading a json resource.
     */
    public function showPage() {
        $this->jsonloader->loadIndex();
        parent::showPage();
    }

	public function getRequest() {
		$this->title                  = $this->setup->getAppNameForPageTitle() . ' :: Admin dashboard';
		$this->menucontainer          = array( new AdminMenu( $this->setup->getAppNameForPageTitle(), Router::ROUTE_ADMIN_DASHBOARD ) );
		$this->leftcontainer          = array( new AdminSidebar( $this->setup->getAppNameForPageTitle(), Router::ROUTE_ADMIN_DASHBOARD, $this->routerContainer ) );

        $resourceGeneralChecks = new StaticTable;
        $resourceGeneralChecks->setHtmlTemplateLoader( $this->htmlTemplateLoader );
        $resourceGeneralChecks->setTitle('General checks');
        $resourceGeneralChecks->addTHead();
        $resourceGeneralChecks->addRow();
        $resourceGeneralChecks->addHeadLineColumn('Name');
        $resourceGeneralChecks->addHeadLineColumn('Satus');
        $resourceGeneralChecks->closeRow();
        $resourceGeneralChecks->closeTHead();
        $resourceGeneralChecks->addTBody();
        foreach ( $this->jsonloader->getResourcesIndex() as $reskey => $resvalue ) {
            $tmpres = $this->jsonloader->loadResource( $reskey );
            $checker = BasicJsonChecker::basicJsonCheckerFactory($tmpres);
            $resourceGeneralChecks->addRow();
            $resourceGeneralChecks->addColumn( $tmpres->name );
            $resourceGeneralChecks->addUnfilteredColumn($checker->isResourceBlockWellStructured() ? 'Ok' : $checker->getErrorsString());
            $resourceGeneralChecks->closeRow();
        }
        $resourceGeneralChecks->closeTBody();

        $resourcesTable = new StaticTable;
        $resourcesTable->setHtmlTemplateLoader( $this->htmlTemplateLoader );
        $resourcesTable->setTitle('Actions');
        $resourcesTable->addTHead();
        $resourcesTable->addRow();
        $resourcesTable->addHeadLineColumn('Name');
        $resourcesTable->addHeadLineColumn('Staus');
        $resourcesTable->closeRow();
        $resourcesTable->closeTHead();
        $resourcesTable->addTBody();
        foreach ( $this->jsonloader->getResourcesIndex() as $restocheck => $restocheckvalue ) {
            $tmprestocheck = $this->jsonloader->loadResource( $restocheck );
            foreach ( $this->jsonloader->getResourcesIndex() as $reskey => $resvalue ) {
                $tmpres = $this->jsonloader->loadResource( $reskey );
                $checker = BasicJsonChecker::basicJsonCheckerFactory( $tmpres );
                if ($checker->isActionPresent($tmprestocheck->name)) {
                    $resourcesTable->addRow();
                    $resourcesTable->addColumn($reskey . ' -> ' . $tmprestocheck->name);
                    $parametersGetter = BasicParameterGetter::basicParameterCheckerFactory( $tmprestocheck, $this->jsonloader );
                    $resourcesTable->addUnfilteredColumn($checker->isActionPresentAndWellStructured($tmprestocheck->name, $parametersGetter->getGetParameters() ) ? 'Ok' : $checker->getErrorsString());
                    $resourcesTable->closeRow();
                }
            }
        }
        $resourcesTable->closeTBody();

		$this->centralcontainer       = array( $resourceGeneralChecks, $resourcesTable );
		$this->secondcentralcontainer = array();

		$this->templateFile = $this->setup->getPrivateTemplateWithSidebarFileName();
	}

}
