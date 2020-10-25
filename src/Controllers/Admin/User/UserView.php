<?php
/**
 * Created by IntelliJ IDEA.
 * User: fabio
 * Date: 21/10/2018
 * Time: 10:40
 */

namespace Fabiom\UglyDuckling\Controllers\Admin\User;

use Fabiom\UglyDuckling\BusinessLogic\User\Daos\UserDao;
use Fabiom\UglyDuckling\Common\Controllers\AdminController;
use Fabiom\UglyDuckling\Common\Router\AdminRouter;
use Fabiom\UglyDuckling\Templates\Blocks\Menus\AdminMenu;
use Fabiom\UglyDuckling\Templates\Blocks\Sidebars\AdminSidebar;
use Fabiom\UglyDuckling\Common\Blocks\BaseHTMLInfo;
use Fabiom\UglyDuckling\Common\Blocks\Button;

/**
 * This class gives a list of all entities loaded in to the system
 */
class UserView extends AdminController {

    private $userDao;

    public function __construct() {
        $this->userDao = new UserDao;
    }

    public $get_validation_rules = array( 'id' => 'required|numeric' );
    public $get_filter_rules     = array( 'id' => 'trim' );

    /**
     * @throws GeneralException
     *
     * $this->getParameters['res'] resource key index
     */
    public function getRequest() {
        $this->userDao->setDBH( $this->applicationBuilder->getDbconnection()->getDBH() );
        $user = $this->userDao->getById( $this->getParameters['id'] );

        $this->title = $this->applicationBuilder->getSetup()->getAppNameForPageTitle() . ' :: User view';

        $info = new BaseHTMLInfo;
        $info->setHtmlTemplateLoader( $this->applicationBuilder->getHtmlTemplateLoader() );
        $info->setTitle( 'User: ' . $user->usr_name . ' ' . $user->usr_surname );
        $info->addUnfilteredParagraph(
            Button::get($this->applicationBuilder->getRouterContainer()->makeRelativeUrl( AdminRouter::ROUTE_ADMIN_USER_EDIT, 'id='.$user->usr_id ), 'Edit', Button::COLOR_GRAY.' '.Button::SMALL ) . ' ' .
            Button::get($this->applicationBuilder->getRouterContainer()->makeRelativeUrl( AdminRouter::ROUTE_ADMIN_USER_EDIT_PASSWORD, 'id='.$user->usr_id ), 'Edit password', Button::COLOR_GRAY.' '.Button::SMALL ) . ' ' .
            Button::get($this->applicationBuilder->getRouterContainer()->makeRelativeUrl( AdminRouter::ROUTE_ADMIN_USER_DELETE, 'id='.$user->usr_id ), 'Del', Button::COLOR_RED.' '.Button::SMALL )
            ,
            '6'
        );
        $info->addTextField('Default group: ', $user->usr_defaultgroup, '6' );
        $info->addTextField('Email: ', $user->usr_email, '6' );
        $info->addDateField('Password updated: ', $user->usr_password_updated 	, '6' );
        $info->addDateField('User created: ', $user->usr_updated , '6' );
        $info->addDateField('User updated: ', $user->usr_created, '6' );

        $this->menucontainer    = array( new AdminMenu( $this->applicationBuilder->getSetup()->getAppNameForPageTitle(), AdminRouter::ROUTE_ADMIN_USER_LIST ) );
        $this->leftcontainer    = array( new AdminSidebar( $this->applicationBuilder->getSetup()->getAppNameForPageTitle(), AdminRouter::ROUTE_ADMIN_USER_LIST, $this->applicationBuilder->getRouterContainer() ) );
        $this->centralcontainer = array( $info );

        $this->templateFile = $this->applicationBuilder->getSetup()->getPrivateTemplateWithSidebarFileName();
    }

}
