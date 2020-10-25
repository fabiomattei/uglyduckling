<?php

namespace Fabiom\UglyDuckling\Controllers\Admin\Group;

use Fabiom\UglyDuckling\BusinessLogic\Group\Daos\UserGroupDao;
use Fabiom\UglyDuckling\Common\Controllers\AdminController;
use Fabiom\UglyDuckling\Common\Router\AdminRouter;
use Fabiom\UglyDuckling\Templates\Blocks\Menus\AdminMenu;
use Fabiom\UglyDuckling\Templates\Blocks\Sidebars\AdminSidebar;
use Fabiom\UglyDuckling\Common\Blocks\BaseHTMLInfo;

/**
 *
 */
class AdminGroupDoc extends AdminController {

    function __construct() {
        $this->userGroupDao = new UserGroupDao;
    }

    public $get_validation_rules = array( 'res' => 'required|max_len,50' );
    public $get_filter_rules     = array( 'res' => 'trim' );

    /**
     * @throws GeneralException
     *
     * $this->getParameters['res'] resource key index
     */
    public function getRequest() {
        $this->userGroupDao->setDBH( $this->applicationBuilder->getDbconnection()->getDBH() );
        $this->resource = $this->applicationBuilder->getJsonloader()->loadResource( $this->getParameters['res'] );

        $this->title = $this->applicationBuilder->getSetup()->getAppNameForPageTitle() . ' :: Admin group doc';

        $info = new BaseHTMLInfo;
        $info->setHtmlTemplateLoader( $this->applicationBuilder->getHtmlTemplateLoader() );
        $info->setTitle( 'Group name: '.$this->resource->name );

        $doctext = '';

        foreach ($this->resource->menu as $menuitem) {
            if (isset($menuitem->submenu)) {
                foreach ($menuitem->submenu as $item) {

                    $tmpres = $this->applicationBuilder->getJsonloader()->loadResource( $item->resource );

                    $docBuilder = BasicDocBuilder::basicJsonDocBuilderFactory( $tmpres, $this->applicationBuilder->getJsonloader() );
                    $doctext .= '\subsection{' . $item->label . '}<br /> ' . $docBuilder->getDocText();
                }

            } else {

                $tmpres = $this->applicationBuilder->getJsonloader()->loadResource( $menuitem->resource );

                $docBuilder = BasicDocBuilder::basicJsonDocBuilderFactory( $tmpres, $this->applicationBuilder->getJsonloader() );
                $doctext .= '\subsection{' . $menuitem->label . '}<br /> ' . $docBuilder->getDocText();

            }
        }

        $info->addUnfilteredParagraph($doctext, 12);

        $this->menucontainer    = array( new AdminMenu( $this->applicationBuilder->getSetup()->getAppNameForPageTitle(), AdminRouter::ROUTE_ADMIN_GROUP_LIST ) );
        $this->leftcontainer    = array( new AdminSidebar( $this->applicationBuilder->getSetup()->getAppNameForPageTitle(), AdminRouter::ROUTE_ADMIN_GROUP_LIST, $this->applicationBuilder->getRouterContainer() ) );
        $this->centralcontainer = array( $info );
        $this->secondcentralcontainer = array();
        $this->thirdcentralcontainer = array();

        $this->templateFile = $this->applicationBuilder->getSetup()->getPrivateTemplateWithSidebarFileName();
    }

}
