<?php

/**
 * Created by Fabio Mattei
 * Date: 21/10/2018
 * Time: 10:38
 */

namespace Fabiom\UglyDuckling\Controllers\Admin\User;

use Fabiom\UglyDuckling\BusinessLogic\User\Daos\UserDao;
use Fabiom\UglyDuckling\Common\Blocks\ButtonForm;
use Fabiom\UglyDuckling\Common\Controllers\AdminController;
use Fabiom\UglyDuckling\Common\Blocks\StaticTable;
use Fabiom\UglyDuckling\Common\Blocks\Button;
use Fabiom\UglyDuckling\Common\Router\AdminRouter;
use Fabiom\UglyDuckling\Templates\Blocks\Menus\AdminMenu;
use Fabiom\UglyDuckling\Templates\Blocks\Sidebars\AdminSidebar;

/**
 * This class gives a list of all entities loaded in to the system
 */
class UserList extends AdminController {

    private $userDao;

    public function __construct() {
        $this->userDao = new UserDao;
    }

    /**
     * @throws GeneralException
     */
    public function getRequest() {
        $this->title = $this->applicationBuilder->getSetup()->getAppNameForPageTitle() . ' :: Users list';

        $table = new StaticTable;
        $table->setHtmlTemplateLoader( $this->applicationBuilder->getHtmlTemplateLoader() );
        $table->setTitle('Users list');
        $table->addButton( 'New user', $this->applicationBuilder->getRouterContainer()->makeRelativeUrl( AdminRouter::ROUTE_ADMIN_USER_NEW ) );

        $table->addTHead();
        $table->addRow();
        $table->addHeadLineColumn('Name');
        $table->addHeadLineColumn('Surname');
        $table->addHeadLineColumn('Main Group');
        $table->addHeadLineColumn(''); // adding one more for actions
        $table->closeRow();
        $table->closeTHead();

        $this->userDao->setDBH( $this->pageStatus->getDbconnection()->getDBH() );
		$this->userDao->setLogger( $this->applicationBuilder->getLogger() );
        $users = $this->userDao->getAll();

        $table->addTBody();
        foreach ( $users as $user ) {
            $table->addRow();
            $table->addColumn( $user->usr_name );
            $table->addColumn( $user->usr_surname );
            $table->addColumn( $user->usr_defaultgroup );
            $table->addUnfilteredColumn(
                Button::get($this->applicationBuilder->getRouterContainer()->makeRelativeUrl( AdminRouter::ROUTE_ADMIN_USER_VIEW, 'id='.$user->usr_id ), 'View', Button::COLOR_GRAY.' '.Button::SMALL ) . ' ' .
                ButtonForm::get($this->applicationBuilder->getRouterContainer()->makeRelativeUrl( AdminRouter::ROUTE_ADMIN_USER_DELETE ), 'Del', $this->pageStatus->getSessionWrapper()->getCsrfToken(), array('usrid' => $user->usr_id), Button::COLOR_RED.' '.Button::SMALL )
            );
            $table->closeRow();
        }
        $table->closeTBody();

        $this->menucontainer    = array( new AdminMenu( $this->applicationBuilder->getSetup()->getAppNameForPageTitle(), AdminRouter::ROUTE_ADMIN_ENTITY_LIST ) );
        $this->leftcontainer    = array( new AdminSidebar( $this->applicationBuilder->getSetup()->getAppNameForPageTitle(), AdminRouter::ROUTE_ADMIN_ENTITY_LIST, $this->applicationBuilder->getRouterContainer() ) );
        $this->centralcontainer = array( $table );

        $this->templateFile = $this->applicationBuilder->getSetup()->getPrivateTemplateWithSidebarFileName();
    }

}
