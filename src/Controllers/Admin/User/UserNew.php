<?php
/**
 * Created by IntelliJ IDEA.
 * User: fabio
 * Date: 21/10/2018
 * Time: 10:39
 */

namespace Firststep\Controllers\Admin\User;

use Firststep\BusinessLogic\User\Daos\UserDao;
use Firststep\Common\Controllers\Controller;
use Firststep\Templates\Blocks\Menus\AdminMenu;
use Firststep\Templates\Blocks\Sidebars\AdminSidebar;
use Firststep\Common\Blocks\BaseForm;
use Firststep\Common\Router\Router;

/**
 * This class gives a list of all entities loaded in to the system
 */
class UserNew extends Controller {

    private $userDao;

    public function __construct() {
        $this->userDao = new UserDao;
    }

    /**
     * @throws GeneralException
     *
     * $this->getParameters['id'] resource key index
     */
    public function getRequest() {
        $this->userDao->setDBH( $this->dbconnection->getDBH() );

        $this->title = $this->setup->getAppNameForPageTitle() . ' :: User';

        $form = new BaseForm;
        $form->setTitle( 'New user: ' );
        $form->addTextField('usr_email', 'Email: ', 'Email', '', '6' );
        $form->addSubmitButton('save', 'Save');

        $this->menucontainer    = array( new AdminMenu( $this->setup->getAppNameForPageTitle(), Router::ROUTE_ADMIN_USER_LIST ) );
        $this->leftcontainer    = array( new AdminSidebar( $this->setup->getAppNameForPageTitle(), Router::ROUTE_ADMIN_USER_LIST, $this->router ) );
        $this->centralcontainer = array( $form );

        $this->templateFile = $this->setup->getPrivateTemplateWithSidebarFileName();
    }

    public $post_validation_rules = array(
        'usr_email' => 'required|valid_email'
    );
    public $post_filter_rules     = array(
        'usr_email' => 'trim|sanitize_email'
    );

    /**
     * @throws GeneralException
     *
     * $this->postParameters['id'] resource key index
     */
    public function postRequest() {
        $this->userDao->setDBH( $this->dbconnection->getDBH() );
        $this->userDao->insert( array(
                'usr_email' => $this->postParameters['usr_email']
            )
        );

        $this->redirectToSecondPreviousPage();
    }

    public function show_post_error_page() {
        $this->userDao->setDBH( $this->dbconnection->getDBH() );

        $this->messages->setError($this->readableErrors);

        $this->title = $this->setup->getAppNameForPageTitle() . ' :: User';

        $form = new BaseForm;
        $form->setTitle( 'New user: ' );
        $form->addTextField('usr_email', 'Email: ', 'Email', '', '6' );
        $form->addSubmitButton('save', 'Save');

        $this->menucontainer    = array( new AdminMenu( $this->setup->getAppNameForPageTitle(), Router::ROUTE_ADMIN_USER_LIST ) );
        $this->leftcontainer    = array( new AdminSidebar( $this->setup->getAppNameForPageTitle(), Router::ROUTE_ADMIN_USER_LIST, $this->router ) );
        $this->centralcontainer = array( $form );
    }

}
