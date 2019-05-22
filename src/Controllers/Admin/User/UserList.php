<?php

/**
 * Created by IntelliJ IDEA.
 * User: fabio
 * Date: 21/10/2018
 * Time: 10:38
 */

namespace Firststep\Controllers\Admin\User;

use Firststep\BusinessLogic\User\Daos\UserDao;
use Firststep\Common\Controllers\Controller;
use Firststep\Common\Blocks\StaticTable;
use Firststep\Common\Blocks\Button;
use Firststep\Common\Router\Router;
use Firststep\Templates\Blocks\Menus\AdminMenu;
use Firststep\Templates\Blocks\Sidebars\AdminSidebar;

/**
 * This class gives a list of all entities loaded in to the system
 */
class UserList extends Controller {

    private $userDao;

    public function __construct() {
        $this->userDao = new UserDao;
    }

    /**
     * @throws GeneralException
     */
    public function getRequest() {
        $this->title = $this->setup->getAppNameForPageTitle() . ' :: Users list';

        $table = new StaticTable;
        $table->setHtmlTemplateLoader( $this->htmlTemplateLoader );
        $table->setTitle('Users list');
        $table->addButton( 'New user', $this->router->make_url( Router::ROUTE_ADMIN_USER_NEW ) );

        $table->addTHead();
        $table->addRow();
        $table->addHeadLineColumn('Name');
        $table->addHeadLineColumn('Surname');
        $table->addHeadLineColumn('Main Group');
        $table->addHeadLineColumn(''); // adding one more for actions
        $table->closeRow();
        $table->closeTHead();

        $this->userDao->setDBH( $this->dbconnection->getDBH() );
        $users = $this->userDao->getAll();

        $table->addTBody();
        foreach ( $users as $user ) {
            $table->addRow();
            $table->addColumn( $user->usr_name );
            $table->addColumn( $user->usr_surname );
            $table->addColumn( $user->usr_defaultgroup );
            $table->addUnfilteredColumn(
                Button::get($this->router->make_url( Router::ROUTE_ADMIN_USER_VIEW, 'id='.$user->usr_id ), 'View', Button::COLOR_GRAY.' '.Button::SMALL ) . ' ' .
                Button::get($this->router->make_url( Router::ROUTE_ADMIN_USER_DELETE, 'id='.$user->usr_id ), 'Del', Button::COLOR_RED.' '.Button::SMALL )
            );
            $table->closeRow();
        }
        $table->closeTBody();

        $this->menucontainer    = array( new AdminMenu( $this->setup->getAppNameForPageTitle(), Router::ROUTE_ADMIN_ENTITY_LIST ) );
        $this->leftcontainer    = array( new AdminSidebar( $this->setup->getAppNameForPageTitle(), Router::ROUTE_ADMIN_ENTITY_LIST, $this->router ) );
        $this->centralcontainer = array( $table );

        $this->templateFile = $this->setup->getPrivateTemplateWithSidebarFileName();
    }

}
