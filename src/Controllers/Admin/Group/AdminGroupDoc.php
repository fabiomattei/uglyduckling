<?php

namespace Fabiom\UglyDuckling\Controllers\Admin\Group;

use Fabiom\UglyDuckling\BusinessLogic\Group\Daos\UserGroupDao;
use Fabiom\UglyDuckling\Common\Controllers\Controller;
use Fabiom\UglyDuckling\Common\Json\Checkers\BasicJsonChecker;
use Fabiom\UglyDuckling\Common\Json\DocBuilders\BasicDocBuilder;
use Fabiom\UglyDuckling\Templates\Blocks\Menus\AdminMenu;
use Fabiom\UglyDuckling\Templates\Blocks\Sidebars\AdminSidebar;
use Fabiom\UglyDuckling\Common\Blocks\BaseHTMLInfo;
use Fabiom\UglyDuckling\Common\Blocks\StaticTable;
use Fabiom\UglyDuckling\Common\Blocks\Button;
use Fabiom\UglyDuckling\Common\Router\Router;

/**
 *
 */
class AdminGroupDoc extends Controller {

    function __construct() {
        $this->userGroupDao = new UserGroupDao;
    }

    public $get_validation_rules = array( 'res' => 'required|max_len,50' );
    public $get_filter_rules     = array( 'res' => 'trim' );

    /**
     * Overwrite parent showPage method in order to add the functionality of loading a json resource.
     */
    public function showPage() {
        $this->jsonloader->loadIndex();
        parent::showPage();
    }

    /**
     * @throws GeneralException
     *
     * $this->getParameters['res'] resource key index
     */
    public function getRequest() {
        $this->userGroupDao->setDBH( $this->dbconnection->getDBH() );
        $this->resource = $this->jsonloader->loadResource( $this->getParameters['res'] );

        $this->title = $this->setup->getAppNameForPageTitle() . ' :: Admin group doc';

        $info = new BaseHTMLInfo;
        $info->setHtmlTemplateLoader( $this->htmlTemplateLoader );
        $info->setTitle( 'Group name: '.$this->resource->name );

        $doctext = '';

        foreach ($this->resource->menu as $menuitem) {
            if (isset($menuitem->submenu)) {
                foreach ($menuitem->submenu as $item) {

                    $tmpres = $this->jsonloader->loadResource( $item->resource );

                    $docBuilder = BasicDocBuilder::basicJsonDocBuilderFactory( $tmpres );
                    $doctext .= '\subsection{' . $item->label . '}\n ' . $docBuilder->getDocText();
                }

            } else {

                $tmpres = $this->jsonloader->loadResource( $menuitem->resource );

                $docBuilder = BasicDocBuilder::basicJsonDocBuilderFactory( $tmpres );
                $doctext .= '\subsection{' . $menuitem->label . '}<br /> ' . $docBuilder->getDocText();

            }
        }

        $info->addParagraph($doctext, 12);

        $this->menucontainer    = array( new AdminMenu( $this->setup->getAppNameForPageTitle(), Router::ROUTE_ADMIN_GROUP_LIST ) );
        $this->leftcontainer    = array( new AdminSidebar( $this->setup->getAppNameForPageTitle(), Router::ROUTE_ADMIN_GROUP_LIST, $this->router ) );
        $this->centralcontainer = array( $info );
        $this->secondcentralcontainer = array();
        $this->thirdcentralcontainer = array();

        $this->templateFile = $this->setup->getPrivateTemplateWithSidebarFileName();
    }

}
