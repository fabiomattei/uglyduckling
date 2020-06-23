<?php

namespace Fabiom\UglyDuckling\Controllers\Admin\Group;

use Fabiom\UglyDuckling\BusinessLogic\Group\Daos\UserGroupDao;
use Fabiom\UglyDuckling\BusinessLogic\User\Daos\UserDao;
use Fabiom\UglyDuckling\Common\Controllers\Controller;
use Fabiom\UglyDuckling\Templates\Blocks\Menus\AdminMenu;
use Fabiom\UglyDuckling\Templates\Blocks\Sidebars\AdminSidebar;
use Fabiom\UglyDuckling\Common\Blocks\BaseHTMLForm;
use Fabiom\UglyDuckling\Common\Router\Router;

class AdminGroupAddUser extends Controller {

    private $userDao;

    public function __construct() {
        $this->userDao = new UserDao;
        $this->userGroupDao = New UserGroupDao();
    }

    public $get_validation_rules = array( 'groupslug' => 'required|max_len,100' );
    public $get_filter_rules     = array( 'groupslug' => 'trim' );

    /**
     * @throws GeneralException
     *
     * $this->getParameters['id'] resource key index
     */
    public function getRequest() {
        $this->userDao->setDBH( $this->applicationBuilder->getDbconnection()->getDBH() );

        $this->title = $this->applicationBuilder->getSetup()->getAppNameForPageTitle() . ' :: Add user to a group';

        $form = new BaseHTMLForm;
        $form->setHtmlTemplateLoader( $this->applicationBuilder->getHtmlTemplateLoader() );
        $form->setTitle( 'Add user to group: ' . $this->getParameters['groupslug'] );
        $form->addDropdownField('usr_id', 'User:', $this->userDao->makeListForDropdown(), '', '6' );
        $form->addHiddenField('groupslug', $this->getParameters['groupslug'] );
        $form->addSubmitButton('save', 'Add');

        $this->menucontainer    = array( new AdminMenu( $this->applicationBuilder->getSetup()->getAppNameForPageTitle(), Router::ROUTE_ADMIN_USER_LIST ) );
        $this->leftcontainer    = array( new AdminSidebar( $this->applicationBuilder->getSetup()->getAppNameForPageTitle(), Router::ROUTE_ADMIN_USER_LIST, $this->applicationBuilder->getRouterContainer() ) );
        $this->centralcontainer = array( $form );

        $this->templateFile = $this->applicationBuilder->getSetup()->getPrivateTemplateWithSidebarFileName();
    }

    public $post_validation_rules = array(
        'usr_id' => 'required|numeric',
        'groupslug' => 'required|alpha_numeric|max_len,100'
    );
    public $post_filter_rules     = array(
        'usr_id' => 'trim',
        'groupslug' => 'trim|sanitize_string'
    );

    /**
     * @throws GeneralException
     *
     * $this->postParameters['id'] resource key index
     */
    public function postRequest() {
        $this->userGroupDao->setDBH( $this->applicationBuilder->getDbconnection()->getDBH() );
        $this->userGroupDao->insert(
            array(
                'ug_groupslug' => $this->postParameters['groupslug'],
                'ug_userid' => $this->postParameters['usr_id']
            )
        );

        $this->redirectToSecondPreviousPage();
    }

    public function show_post_error_page() {
        $this->userDao->setDBH( $this->applicationBuilder->getDbconnection()->getDBH() );

        $this->messages->setError($this->readableErrors);

        $this->title = $this->applicationBuilder->getSetup()->getAppNameForPageTitle() . ' :: Add user to a group';

        $form = new BaseHTMLForm;
        $form->setHtmlTemplateLoader( $this->applicationBuilder->getHtmlTemplateLoader() );
        $form->setTitle( 'Add user to a group');
        $form->addDropdownField('usr_id', 'Users:', $this->userDao->makeListForDropdown(), '', '6' );
        $form->addHiddenField('groupslug', $this->getParameters['groupslug'] );
        $form->addSubmitButton('save', 'Add');

        $this->menucontainer    = array( new AdminMenu( $this->applicationBuilder->getSetup()->getAppNameForPageTitle(), Router::ROUTE_ADMIN_USER_LIST ) );
        $this->leftcontainer    = array( new AdminSidebar( $this->applicationBuilder->getSetup()->getAppNameForPageTitle(), Router::ROUTE_ADMIN_USER_LIST, $this->applicationBuilder->getRouterContainer() ) );
        $this->centralcontainer = array( $form );
    }
}
