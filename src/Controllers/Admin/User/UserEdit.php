<?php
/**
 * Created by IntelliJ IDEA.
 * User: fabio
 * Date: 21/10/2018
 * Time: 10:39
 */

namespace Firststep\Controllers\Admin\User;

use Firststep\BusinessLogic\Group\Daos\UserGroupDao;
use Firststep\BusinessLogic\User\Daos\UserDao;
use Firststep\Common\Controllers\Controller;
use Firststep\Templates\Blocks\Menus\AdminMenu;
use Firststep\Templates\Blocks\Sidebars\AdminSidebar;
use Firststep\Common\Blocks\BaseHTMLForm;
use Firststep\Common\Router\Router;

/**
 * This class gives a list of all entities loaded in to the system
 */
class UserEdit extends Controller {

    private $userDao;
    private $userGroupDao;

    public function __construct() {
        $this->userDao = new UserDao;
        $this->userGroupDao = new UserGroupDao();
    }

    public $get_validation_rules = array( 'id' => 'required|numeric' );
    public $get_filter_rules     = array( 'id' => 'trim' );

    /**
     * @throws GeneralException
     *
     * $this->getParameters['id'] resource key index
     */
    public function getRequest() {
        $this->userDao->setDBH( $this->dbconnection->getDBH() );
        $this->userGroupDao->setDBH( $this->dbconnection->getDBH() );

        $user = $this->userDao->getById( $this->getParameters['id'] );

        $this->title = $this->setup->getAppNameForPageTitle() . ' :: User edit';

        $form = new BaseHTMLForm;
        $form->setHtmlTemplateLoader( $this->htmlTemplateLoader );
        $form->setTitle( 'User: ' . $user->usr_name . ' ' . $user->usr_surname );
        $form->addDropdownField( 'usr_defaultgroup', 'Default group:', $this->userGroupDao->makeListForDropdownByUserId( $this->getParameters['id'] ), $user->usr_defaultgroup, '6' );
        $form->addTextField('usr_email', 'Email: ', 'Email', $user->usr_email, '6' );
        $form->addHiddenField('usr_id', $user->usr_id);
        $form->addSubmitButton('save', 'Save');

        $this->menucontainer    = array( new AdminMenu( $this->setup->getAppNameForPageTitle(), Router::ROUTE_ADMIN_USER_LIST ) );
        $this->leftcontainer    = array( new AdminSidebar( $this->setup->getAppNameForPageTitle(), Router::ROUTE_ADMIN_USER_LIST, $this->router ) );
        $this->centralcontainer = array( $form );

        $this->templateFile = $this->setup->getPrivateTemplateWithSidebarFileName();
    }

    public $post_validation_rules = array(
        'usr_id' => 'required|numeric',
        'usr_defaultgroup' => 'required|alpha_numeric|max_len,100',
        'usr_email' => 'required|valid_email'
    );
    public $post_filter_rules     = array(
        'usr_id' => 'trim',
        'usr_defaultgroup' => 'trim|sanitize_string',
        'usr_email' => 'trim|sanitize_email'
    );

    /**
     * @throws GeneralException
     *
     * $this->postParameters['id'] resource key index
     */
    public function postRequest() {
        $this->userDao->setDBH( $this->dbconnection->getDBH() );
        $this->userDao->update( $this->postParameters['usr_id'], array(
            'usr_defaultgroup' => $this->postParameters['usr_defaultgroup'],
            'usr_email' => $this->postParameters['usr_email']
            )
        );

        $this->redirectToSecondPreviousPage();
    }

    public function show_post_error_page() {
        $this->userDao->setDBH( $this->dbconnection->getDBH() );
        $this->userGroupDao->setDBH( $this->dbconnection->getDBH() );

        $user = $this->userDao->getById( $this->getParameters['id'] );

        $this->messages->setError($this->readableErrors);

        $this->title = $this->setup->getAppNameForPageTitle() . ' :: User view';

        $form = new BaseHTMLForm;
        $form->setHtmlTemplateLoader( $this->htmlTemplateLoader );
        $form->setTitle( 'User: ' . $user->usr_name . ' ' . $user->usr_surname );
        $form->addDropdownField( 'usr_defaultgroup', 'Default group:', $this->userGroupDao->makeListForDropdownByUserId( $this->getParameters['id'] ), $user->usr_defaultgroup, '6' );
        $form->addTextField('usr_email', 'Email: ', 'Email', $user->usr_email, '6' );
        $form->addHiddenField('usr_id', $user->usr_id);
        $form->addSubmitButton('save', 'Save');

        $this->menucontainer    = array( new AdminMenu( $this->setup->getAppNameForPageTitle(), Router::ROUTE_ADMIN_USER_LIST ) );
        $this->leftcontainer    = array( new AdminSidebar( $this->setup->getAppNameForPageTitle(), Router::ROUTE_ADMIN_USER_LIST, $this->router ) );
        $this->centralcontainer = array( $form );
    }

}
