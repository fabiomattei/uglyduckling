<?php

namespace Fabiom\UglyDuckling\Controllers\Admin\Dashboard;

use Fabiom\UglyDuckling\Common\Controllers\Controller;
use Fabiom\UglyDuckling\Common\Json\Checkers\BasicJsonChecker;
use Fabiom\UglyDuckling\Common\Json\Parameters\BasicParameterGetter;
use Fabiom\UglyDuckling\Common\Router\AdminRouter;
use Fabiom\UglyDuckling\Templates\Blocks\Menus\AdminMenu;
use Fabiom\UglyDuckling\Templates\Blocks\Sidebars\AdminSidebar;
use Fabiom\UglyDuckling\Common\Blocks\StaticTable;

/**
 * 
 */
class AdminDashboard extends Controller {

    /**
     * Overwrite parent showPage method in order to add the functionality of loading a json resource.
     */
    public function showPage() {
        $this->applicationBuilder->getJsonloader()->loadIndex();
        parent::showPage();
    }

	public function getRequest() {
		$this->title                  = $this->applicationBuilder->getSetup()->getAppNameForPageTitle() . ' :: Admin dashboard';
		$this->menucontainer          = array( new AdminMenu( $this->applicationBuilder->getSetup()->getAppNameForPageTitle(), AdminRouter::ROUTE_ADMIN_DASHBOARD ) );
		$this->leftcontainer          = array( new AdminSidebar( $this->applicationBuilder->getSetup()->getAppNameForPageTitle(), AdminRouter::ROUTE_ADMIN_DASHBOARD, $this->applicationBuilder->getRouterContainer() ) );

        $resourceGeneralChecks = new StaticTable;
        $resourceGeneralChecks->setHtmlTemplateLoader( $this->applicationBuilder->getHtmlTemplateLoader() );
        $resourceGeneralChecks->setTitle('General checks');
        $resourceGeneralChecks->addTHead();
        $resourceGeneralChecks->addRow();
        $resourceGeneralChecks->addHeadLineColumn('Name');
        $resourceGeneralChecks->addHeadLineColumn('Satus');
        $resourceGeneralChecks->closeRow();
        $resourceGeneralChecks->closeTHead();
        $resourceGeneralChecks->addTBody();
        foreach ( $this->applicationBuilder->getJsonloader()->getResourcesIndex() as $reskey => $resvalue ) {
            $tmpres = $this->applicationBuilder->getJsonloader()->loadResource( $reskey );
            $checker = BasicJsonChecker::basicJsonCheckerFactory($tmpres);
            $resourceGeneralChecks->addRow();
            $resourceGeneralChecks->addColumn( $tmpres->name );
            $resourceGeneralChecks->addUnfilteredColumn($checker->isResourceBlockWellStructured() ? 'Ok' : $checker->getErrorsString());
            $resourceGeneralChecks->closeRow();
        }
        $resourceGeneralChecks->closeTBody();

        $resourcesTable = new StaticTable;
        $resourcesTable->setHtmlTemplateLoader( $this->applicationBuilder->getHtmlTemplateLoader() );
        $resourcesTable->setTitle('Actions');
        $resourcesTable->addTHead();
        $resourcesTable->addRow();
        $resourcesTable->addHeadLineColumn('Name');
        $resourcesTable->addHeadLineColumn('Staus');
        $resourcesTable->closeRow();
        $resourcesTable->closeTHead();
        $resourcesTable->addTBody();
        foreach ( $this->applicationBuilder->getJsonloader()->getResourcesIndex() as $restocheck => $restocheckvalue ) {
            $tmprestocheck = $this->applicationBuilder->getJsonloader()->loadResource( $restocheck );
            foreach ( $this->applicationBuilder->getJsonloader()->getResourcesIndex() as $reskey => $resvalue ) {
                $tmpres = $this->applicationBuilder->getJsonloader()->loadResource( $reskey );
                $checker = BasicJsonChecker::basicJsonCheckerFactory( $tmpres );
                if ($checker->isActionPresent($tmprestocheck->name)) {
                    $resourcesTable->addRow();
                    $resourcesTable->addColumn($reskey . ' -> ' . $tmprestocheck->name);
                    $parametersGetter = BasicParameterGetter::basicParameterCheckerFactory( $tmprestocheck, $this->applicationBuilder->getJsonloader() );
                    $resourcesTable->addUnfilteredColumn($checker->isActionPresentAndWellStructured($tmprestocheck->name, $parametersGetter->getGetParameters() ) ? 'Ok' : $checker->getErrorsString());
                    $resourcesTable->closeRow();
                }
            }
        }
        $resourcesTable->closeTBody();

		$this->centralcontainer       = array( $resourceGeneralChecks, $resourcesTable );
		$this->secondcentralcontainer = array();

		$this->templateFile = $this->applicationBuilder->getSetup()->getPrivateTemplateWithSidebarFileName();
	}

}
