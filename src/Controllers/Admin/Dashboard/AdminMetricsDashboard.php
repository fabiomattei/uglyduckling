<?php

namespace Fabiom\UglyDuckling\Controllers\Admin\Dashboard;

use Fabiom\UglyDuckling\Common\Controllers\Controller;
use Fabiom\UglyDuckling\Common\Json\Metrics\BaseResourceMetric;
use Fabiom\UglyDuckling\Common\Json\Parameters\BasicParameterGetter;
use Fabiom\UglyDuckling\Templates\Blocks\Menus\AdminMenu;
use Fabiom\UglyDuckling\Templates\Blocks\Sidebars\AdminSidebar;
use Fabiom\UglyDuckling\Common\Blocks\StaticTable;
use Fabiom\UglyDuckling\Common\Router\ResourceRouter;

/**
 * This controller shows to the user an interface where all the resources are listed and 
 * the metric is calculated for each one of them
 */
class AdminMetricsDashboard extends Controller {

    /**
     * Overwrite parent showPage method in order to add the functionality of loading a json resource.
     */
    public function showPage() {
        $this->applicationBuilder->getJsonloader()->loadIndex();
        parent::showPage();
    }

	public function getRequest() {
		$this->title                  = $this->applicationBuilder->getSetup()->getAppNameForPageTitle() . ' :: Admin metrics';
		$this->menucontainer          = array( new AdminMenu( $this->applicationBuilder->getSetup()->getAppNameForPageTitle(), ResourceRouter::ROUTE_ADMIN_DASHBOARD ) );
		$this->leftcontainer          = array( new AdminSidebar( $this->applicationBuilder->getSetup()->getAppNameForPageTitle(), ResourceRouter::ROUTE_ADMIN_DASHBOARD, $this->applicationBuilder->getRouterContainer() ) );

        $resourceGeneralChecks = new StaticTable;
        $resourceGeneralChecks->setHtmlTemplateLoader( $this->applicationBuilder->getHtmlTemplateLoader() );
        $resourceGeneralChecks->setTitle('IFPUG');
        $resourceGeneralChecks->addTHead();
        $resourceGeneralChecks->addRow();
        $resourceGeneralChecks->addHeadLineColumn('Resource Name');
        $resourceGeneralChecks->addHeadLineColumn('FP');
        $resourceGeneralChecks->closeRow();
        $resourceGeneralChecks->closeTHead();
        $resourceGeneralChecks->addTBody();
        foreach ( $this->applicationBuilder->getJsonloader()->getResourcesIndex() as $reskey => $resvalue ) {
            $tmpres = $this->applicationBuilder->getJsonloader()->loadResource( $reskey );
            $checker = BaseResourceMetric::basicResourceMetricFactory($tmpres);
            $resourceGeneralChecks->addRow();
            $resourceGeneralChecks->addColumn( $tmpres->name );
            $resourceGeneralChecks->addColumn( $checker->getFunctionPoints() );
            $resourceGeneralChecks->closeRow();
        }
        $resourceGeneralChecks->closeTBody();

		$this->centralcontainer       = array( $resourceGeneralChecks );
		$this->secondcentralcontainer = array();

		$this->templateFile = $this->applicationBuilder->getSetup()->getPrivateTemplateWithSidebarFileName();
	}

}
