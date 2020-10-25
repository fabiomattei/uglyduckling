<?php

namespace Fabiom\UglyDuckling\Controllers\Admin\Group;

use Fabiom\UglyDuckling\BusinessLogic\Group\Daos\UserGroupDao;
use Fabiom\UglyDuckling\Common\Controllers\AdminController;
use Fabiom\UglyDuckling\Common\Router\AdminRouter;
use Fabiom\UglyDuckling\Templates\Blocks\Menus\AdminMenu;
use Fabiom\UglyDuckling\Templates\Blocks\Sidebars\AdminSidebar;
use Fabiom\UglyDuckling\Common\Blocks\BaseHTMLInfo;
use Fabiom\UglyDuckling\Common\Blocks\StaticTable;
use Fabiom\UglyDuckling\Common\Blocks\Button;

/**
 *
 */
class AdminGroupView extends AdminController {

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

        $this->title = $this->applicationBuilder->getSetup()->getAppNameForPageTitle() . ' :: Admin group view';

        $info = new BaseHTMLInfo;
        $info->setHtmlTemplateLoader( $this->applicationBuilder->getHtmlTemplateLoader() );
        $info->setTitle( 'Group name: '.$this->resource->name );

        $users = $this->userGroupDao->getUsersByGroupSlug( $this->resource->name );

        $userTable = new StaticTable;
        $userTable->setHtmlTemplateLoader( $this->applicationBuilder->getHtmlTemplateLoader() );
        $userTable->setTitle("Users that belong to this group");
        $userTable->addButton('Add a user to this group', $this->applicationBuilder->getRouterContainer()->makeRelativeUrl( AdminRouter::ROUTE_ADMIN_GROUP_ADD_USER, 'groupslug='.$this->resource->name ));
        $userTable->addTHead();
        $userTable->addRow();
        $userTable->addHeadLineColumn('Name');
        $userTable->addHeadLineColumn(''); // adding one more for actions
        $userTable->closeRow();
        $userTable->closeTHead();
        $userTable->addTBody();
        foreach ( $users as $res ) {
            $userTable->addRow();
            $userTable->addColumn($res->usr_name.' '.$res->usr_surname);
            $userTable->addUnfilteredColumn( Button::get($this->applicationBuilder->getRouterContainer()->makeRelativeUrl( AdminRouter::ROUTE_ADMIN_GROUP_REMOVE_USER, 'res='.$this->resource->name.'&usrid='.$res->usr_id ), 'Remove', Button::COLOR_GRAY.' '.Button::SMALL ) );
            $userTable->closeRow();
        }
        $userTable->closeTBody();

        $resourcesTable = new StaticTable;
        $resourcesTable->setHtmlTemplateLoader( $this->applicationBuilder->getHtmlTemplateLoader() );
        $resourcesTable->setTitle("Resources this group has access to");
        $resourcesTable->addTHead();
        $resourcesTable->addRow();
        $resourcesTable->addHeadLineColumn('Name');
        $resourcesTable->addHeadLineColumn('Path');
        $resourcesTable->addHeadLineColumn('Type'); // adding one more for actions
        $resourcesTable->closeRow();
        $resourcesTable->closeTHead();
        $resourcesTable->addTBody();
        foreach ( $this->applicationBuilder->getJsonloader()->getResourcesIndex() as $reskey => $resvalue ) {
            $tmpres = $this->applicationBuilder->getJsonloader()->loadResource( $reskey );
            if ( isset($tmpres->allowedgroups) AND in_array( $this->resource->name, $tmpres->allowedgroups) ) {
                $resourcesTable->addRow();
                $resourcesTable->addColumn($reskey);
                $resourcesTable->addColumn($resvalue->path);
                $resourcesTable->addColumn($resvalue->type);
                $resourcesTable->closeRow();
            }
        }
        $resourcesTable->closeTBody();

        $this->menucontainer    = array( new AdminMenu( $this->applicationBuilder->getSetup()->getAppNameForPageTitle(), AdminRouter::ROUTE_ADMIN_GROUP_LIST ) );
        $this->leftcontainer    = array( new AdminSidebar( $this->applicationBuilder->getSetup()->getAppNameForPageTitle(), AdminRouter::ROUTE_ADMIN_GROUP_LIST, $this->applicationBuilder->getRouterContainer() ) );
        $this->centralcontainer = array( $info );
        $this->secondcentralcontainer = array( $userTable );
        $this->thirdcentralcontainer = array( $resourcesTable );

        $this->templateFile = $this->applicationBuilder->getSetup()->getPrivateTemplateWithSidebarFileName();
    }

}
