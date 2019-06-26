<?php
/**
 * Created by IntelliJ IDEA.
 * User: fabio
 * Date: 21/10/2018
 * Time: 10:40
 */

namespace Firststep\Controllers\Admin\User;

use Firststep\BusinessLogic\User\Daos\UserDao;
use Firststep\Common\Controllers\Controller;
use Firststep\Templates\Blocks\Menus\AdminMenu;
use Firststep\Templates\Blocks\Sidebars\AdminSidebar;
use Firststep\Common\Blocks\BaseHTMLInfo;
use Firststep\Common\Blocks\Button;
use Firststep\Common\Router\Router;

/**
 * This class gives a list of all entities loaded in to the system
 */
class UserView extends Controller {

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
        $this->userDao->setDBH( $this->dbconnection->getDBH() );
        $user = $this->userDao->getById( $this->getParameters['id'] );

        $this->title = $this->setup->getAppNameForPageTitle() . ' :: User view';

        $info = new BaseHTMLInfo;
        $info->setHtmlTemplateLoader( $this->htmlTemplateLoader );
        $info->setTitle( 'User: ' . $user->usr_name . ' ' . $user->usr_surname );
        $info->addParagraph(
            Button::get($this->router->make_url( Router::ROUTE_ADMIN_USER_EDIT, 'id='.$user->usr_id ), 'Edit', Button::COLOR_GRAY.' '.Button::SMALL ) . ' ' .
            Button::get($this->router->make_url( Router::ROUTE_ADMIN_USER_EDIT_PASSWORD, 'id='.$user->usr_id ), 'Edit password', Button::COLOR_GRAY.' '.Button::SMALL ) . ' ' .
            Button::get($this->router->make_url( Router::ROUTE_ADMIN_USER_DELETE, 'id='.$user->usr_id ), 'Del', Button::COLOR_RED.' '.Button::SMALL )
            ,
            '6'
        );
        $info->addTextField('Default group: ', $user->usr_defaultgroup, '6' );
        $info->addTextField('Email: ', $user->usr_email, '6' );
        $info->addDateField('Password updated: ', $user->usr_password_updated 	, '6' );
        $info->addDateField('User created: ', $user->usr_updated , '6' );
        $info->addDateField('User updated: ', $user->usr_created, '6' );

        $this->menucontainer    = array( new AdminMenu( $this->setup->getAppNameForPageTitle(), Router::ROUTE_ADMIN_USER_LIST ) );
        $this->leftcontainer    = array( new AdminSidebar( $this->setup->getAppNameForPageTitle(), Router::ROUTE_ADMIN_USER_LIST, $this->router ) );
        $this->centralcontainer = array( $info );

        $this->templateFile = $this->setup->getPrivateTemplateWithSidebarFileName();
    }

}
