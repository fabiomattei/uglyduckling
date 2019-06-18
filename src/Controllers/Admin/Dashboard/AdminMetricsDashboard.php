<?php

namespace Firststep\Controllers\Admin\Dashboard;

use Firststep\Common\Controllers\Controller;
use Firststep\Common\Json\Metrics\BaseResourceMetric;
use Firststep\Common\Json\Parameters\BasicParameterGetter;
use Firststep\Templates\Blocks\Menus\AdminMenu;
use Firststep\Templates\Blocks\Sidebars\AdminSidebar;
use Firststep\Common\Blocks\StaticTable;
use Firststep\Common\Router\Router;

/**
 * 
 */
class AdminMetricsDashboard extends Controller {

    /**
     * Overwrite parent showPage method in order to add the functionality of loading a json resource.
     */
    public function showPage() {
        $this->jsonloader->loadIndex();
        parent::showPage();
    }

	public function getRequest() {
		$this->title                  = $this->setup->getAppNameForPageTitle() . ' :: Admin metrics';
		$this->menucontainer          = array( new AdminMenu( $this->setup->getAppNameForPageTitle(), Router::ROUTE_ADMIN_DASHBOARD ) );
		$this->leftcontainer          = array( new AdminSidebar( $this->setup->getAppNameForPageTitle(), Router::ROUTE_ADMIN_DASHBOARD, $this->router ) );

        $resourceGeneralChecks = new StaticTable;
        $resourceGeneralChecks->setHtmlTemplateLoader( $this->htmlTemplateLoader );
        $resourceGeneralChecks->setTitle('IFPUG');
        $resourceGeneralChecks->addTHead();
        $resourceGeneralChecks->addRow();
        $resourceGeneralChecks->addHeadLineColumn('Resource Name');
        $resourceGeneralChecks->addHeadLineColumn('FP');
        $resourceGeneralChecks->closeRow();
        $resourceGeneralChecks->closeTHead();
        $resourceGeneralChecks->addTBody();
        foreach ( $this->jsonloader->getResourcesIndex() as $reskey => $resvalue ) {
            $tmpres = $this->jsonloader->loadResource( $reskey );
            $checker = BaseResourceMetric::basicResourceMetricFactory($tmpres);
            $resourceGeneralChecks->addRow();
            $resourceGeneralChecks->addColumn( $tmpres->name );
            $resourceGeneralChecks->addColumn( $checker->getFunctionPoints() );
            $resourceGeneralChecks->closeRow();
        }
        $resourceGeneralChecks->closeTBody();

		$this->centralcontainer       = array( $resourceGeneralChecks );
		$this->secondcentralcontainer = array();

		$this->templateFile = $this->setup->getPrivateTemplateWithSidebarFileName();
	}

}
