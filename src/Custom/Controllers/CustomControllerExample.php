<?php

namespace Fabiom\UglyDuckling\Custom\Controllers;

use Fabiom\UglyDuckling\Common\Controllers\Controller;

class CustomControllerExample extends Controller {

    const CONTROLLER_NAME = 'customcontrollerexample';

    public function getRequest() {
        $this->title                  = $this->setup->getAppNameForPageTitle() . ' :: Admin dashboard';
        $this->menucontainer          = array( new AdminMenu( $this->setup->getAppNameForPageTitle(), Router::ROUTE_ADMIN_DASHBOARD ) );
        $this->leftcontainer          = array( new AdminSidebar( $this->setup->getAppNameForPageTitle(), Router::ROUTE_ADMIN_DASHBOARD, $this->router ) );

        $this->templateFile = $this->setup->getPrivateTemplateWithSidebarFileName();
    }

    public function postRequest() {

    }

}
