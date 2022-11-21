<?php
/**
 * Created by Fabio Mattei
 * 
 * Date: 21/10/2018
 * Time: 10:39
 */

namespace Fabiom\UglyDuckling\Controllers\Admin\User;

use Fabiom\UglyDuckling\BusinessLogic\Group\Daos\UserGroupDao;
use Fabiom\UglyDuckling\BusinessLogic\User\Daos\UserDao;
use Fabiom\UglyDuckling\Common\Controllers\AdminController;
use Fabiom\UglyDuckling\Common\Router\AdminRouter;
use Fabiom\UglyDuckling\Templates\Blocks\Menus\AdminMenu;
use Fabiom\UglyDuckling\Templates\Blocks\Sidebars\AdminSidebar;
use Fabiom\UglyDuckling\Common\Blocks\BaseHTMLForm;

/**
 * This class gives a list of all entities loaded in to the system
 */
class UserEdit extends AdminController {

    private $userDao;
    private $userGroupDao;

    public function __construct() {
        $this->userDao = new UserDao;
        $this->userGroupDao = new UserGroupDao();
    }

    public $get_validation_rules = array( 'id' => 'required|alpha_numeric_dash' );
    public $get_filter_rules     = array( 'id' => 'trim' );

    /**
     * @throws GeneralException
     *
     * $this->getParameters['id'] resource key index
     */
    public function getRequest() {
        $this->userDao->setDBH( $this->pageStatus->getDbconnection()->getDBH() );
		$this->userDao->setLogger( $this->applicationBuilder->getLogger() );
        $this->userGroupDao->setDBH( $this->pageStatus->getDbconnection()->getDBH() );
		$this->userGroupDao->setLogger( $this->applicationBuilder->getLogger() );

        $user = $this->userDao->getById( $this->getParameters['id'] );

        $this->title = $this->applicationBuilder->getSetup()->getAppNameForPageTitle() . ' :: User edit';

        $form = new BaseHTMLForm;
        $form->setHtmlTemplateLoader( $this->applicationBuilder->getHtmlTemplateLoader() );
        $form->setTitle( 'User: ' . $user->usr_name . ' ' . $user->usr_surname );
        $form->addHiddenField('csrftoken', $_SESSION['csrftoken'] );
        $form->addDropdownField( 'usr_defaultgroup', 'Default group:', $this->userGroupDao->makeListForDropdownByUserId( $this->getParameters['id'] ), $user->usr_defaultgroup, '6' );
        $form->addTextField('usr_email', 'Email: ', 'Email', $user->usr_email, '6' );
        $form->addTextField('usr_name', 'Name: ', 'Name', $user->usr_name, '6' );
        $form->addTextField('usr_surname', 'Surname: ', 'Surname', $user->usr_surname, '6' );
        $form->addHiddenField('usr_id', $user->usr_id);
        $form->addSubmitButton('save', 'Save');

        $this->menucontainer    = array( new AdminMenu( $this->applicationBuilder->getSetup()->getAppNameForPageTitle(), AdminRouter::ROUTE_ADMIN_USER_LIST ) );
        $this->leftcontainer    = array( new AdminSidebar( $this->applicationBuilder->getSetup()->getAppNameForPageTitle(), AdminRouter::ROUTE_ADMIN_USER_LIST, $this->applicationBuilder->getRouterContainer() ) );
        $this->centralcontainer = array( $form );

        $this->templateFile = $this->applicationBuilder->getSetup()->getPrivateTemplateWithSidebarFileName();
    }

    public $post_validation_rules = array(
        'usr_id' => 'required|alpha_numeric_dash',
        'usr_defaultgroup' => 'required|alpha_numeric|max_len,100',
        'usr_email' => 'required|valid_email',
        'usr_name' => 'max_len,100',
        'usr_surname' => 'max_len,100'
    );
    public $post_filter_rules     = array(
        'usr_id' => 'trim',
        'usr_defaultgroup' => 'trim|sanitize_string',
        'usr_email' => 'trim|sanitize_email',
        'usr_name' => 'trim',
        'usr_surname' => 'trim'
    );

    /**
     * @throws GeneralException
     *
     * $this->postParameters['id'] resource key index
     */
    public function postRequest() {
        $this->userDao->setDBH( $this->pageStatus->getDbconnection()->getDBH() );
        $this->userDao->update( $this->postParameters['usr_id'], array(
            'usr_defaultgroup' => $this->postParameters['usr_defaultgroup'],
            'usr_email' => $this->postParameters['usr_email'],
            'usr_name' => $this->postParameters['usr_name'],
            'usr_surname' => $this->postParameters['usr_surname']
            )
        );

        $this->redirectToSecondPreviousPage();
    }

    public function show_post_error_page() {
        $this->userDao->setDBH( $this->pageStatus->getDbconnection()->getDBH() );
        $this->userGroupDao->setDBH( $this->pageStatus->getDbconnection()->getDBH() );

        $user = $this->userDao->getById( $this->getParameters['id'] );

        $this->applicationBuilder->getMessages()->setError($this->readableErrors);

        $this->title = $this->applicationBuilder->getSetup()->getAppNameForPageTitle() . ' :: User view';

        $form = new BaseHTMLForm;
        $form->setHtmlTemplateLoader( $this->applicationBuilder->getHtmlTemplateLoader() );
        $form->setTitle( 'User: ' . $user->usr_name . ' ' . $user->usr_surname );
        $form->addDropdownField( 'usr_defaultgroup', 'Default group:', $this->userGroupDao->makeListForDropdownByUserId( $this->getParameters['id'] ), $user->usr_defaultgroup, '6' );
        $form->addTextField('usr_email', 'Email: ', 'Email', $user->usr_email, '6' );
        $form->addTextField('usr_name', 'Name: ', 'Name', $user->usr_name, '6' );
        $form->addTextField('usr_surname', 'Surname: ', 'Surname', $user->usr_surname, '6' );
        $form->addHiddenField('usr_id', $user->usr_id);
        $form->addSubmitButton('save', 'Save');

        $this->menucontainer    = array( new AdminMenu( $this->applicationBuilder->getSetup()->getAppNameForPageTitle(), AdminRouter::ROUTE_ADMIN_USER_LIST ) );
        $this->leftcontainer    = array( new AdminSidebar( $this->applicationBuilder->getSetup()->getAppNameForPageTitle(), AdminRouter::ROUTE_ADMIN_USER_LIST, $this->applicationBuilder->getRouterContainer() ) );
        $this->centralcontainer = array( $form );
    }

}
