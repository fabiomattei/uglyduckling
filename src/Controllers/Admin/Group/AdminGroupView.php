<?php

namespace Fabiom\UglyDuckling\Controllers\Admin\Group;

use Fabiom\UglyDuckling\BusinessLogic\Group\Daos\UserGroupDao;
use Fabiom\UglyDuckling\Common\Controllers\Controller;
use Fabiom\UglyDuckling\Templates\Blocks\Menus\AdminMenu;
use Fabiom\UglyDuckling\Templates\Blocks\Sidebars\AdminSidebar;
use Fabiom\UglyDuckling\Common\Blocks\BaseHTMLInfo;
use Fabiom\UglyDuckling\Common\Blocks\StaticTable;
use Fabiom\UglyDuckling\Common\Blocks\Button;
use Fabiom\UglyDuckling\Common\Router\Router;

/**
 *
 */
class AdminGroupView extends Controller {

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

        $this->title = $this->setup->getAppNameForPageTitle() . ' :: Admin group view';

        $info = new BaseHTMLInfo;
        $info->setHtmlTemplateLoader( $this->htmlTemplateLoader );
        $info->setTitle( 'Group name: '.$this->resource->name );

        $users = $this->userGroupDao->getUsersByGroupSlug( $this->resource->name );

        $userTable = new StaticTable;
        $userTable->setHtmlTemplateLoader( $this->htmlTemplateLoader );
        $userTable->setTitle("Users");
        $userTable->addButton('Add', $this->router->make_url( Router::ROUTE_ADMIN_GROUP_ADD_USER, 'groupslug='.$this->resource->name ));
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
            $userTable->addUnfilteredColumn( Button::get($this->router->make_url( Router::ROUTE_ADMIN_GROUP_REMOVE_USER, 'res='.$this->resource->name.'&usrid='.$res->usr_id ), 'Remove', Button::COLOR_GRAY.' '.Button::SMALL ) );
            $userTable->closeRow();
        }
        $userTable->closeTBody();

        $resourcesTable = new StaticTable;
        $resourcesTable->setHtmlTemplateLoader( $this->htmlTemplateLoader );
        $resourcesTable->setTitle("Resources this group has access to");
        $resourcesTable->addTHead();
        $resourcesTable->addRow();
        $resourcesTable->addHeadLineColumn('Name');
        $resourcesTable->addHeadLineColumn('Path');
        $resourcesTable->addHeadLineColumn('Type'); // adding one more for actions
        $resourcesTable->closeRow();
        $resourcesTable->closeTHead();
        $resourcesTable->addTBody();
        foreach ( $this->jsonloader->getResourcesIndex() as $reskey => $resvalue ) {
            $tmpres = $this->jsonloader->loadResource( $reskey );
            if ( isset($tmpres->allowedgroups) AND in_array( $this->resource->name, $tmpres->allowedgroups) ) {
                $resourcesTable->addRow();
                $resourcesTable->addColumn($reskey);
                $resourcesTable->addColumn($resvalue->path);
                $resourcesTable->addColumn($resvalue->type);
                $resourcesTable->closeRow();
            }
        }
        $resourcesTable->closeTBody();

        $this->menucontainer    = array( new AdminMenu( $this->setup->getAppNameForPageTitle(), Router::ROUTE_ADMIN_GROUP_LIST ) );
        $this->leftcontainer    = array( new AdminSidebar( $this->setup->getAppNameForPageTitle(), Router::ROUTE_ADMIN_GROUP_LIST, $this->router ) );
        $this->centralcontainer = array( $info );
        $this->secondcentralcontainer = array( $userTable );
        $this->thirdcentralcontainer = array( $resourcesTable );

        $this->templateFile = $this->setup->getPrivateTemplateWithSidebarFileName();
    }

}
