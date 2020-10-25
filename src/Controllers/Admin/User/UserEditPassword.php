<?php
/**
 * Created by IntelliJ IDEA.
 * User: fabio
 * Date: 21/10/2018
 * Time: 10:39
 */

namespace Fabiom\UglyDuckling\Controllers\Admin\User;

use Fabiom\UglyDuckling\BusinessLogic\User\Daos\UserDao;
use Fabiom\UglyDuckling\Common\Controllers\AdminController;
use Fabiom\UglyDuckling\Common\Router\AdminRouter;
use Fabiom\UglyDuckling\Templates\Blocks\Menus\AdminMenu;
use Fabiom\UglyDuckling\Templates\Blocks\Sidebars\AdminSidebar;
use Fabiom\UglyDuckling\Common\Blocks\BaseHTMLForm;

/**
 * This class gives a list of all entities loaded in to the system
 */
class UserEditPassword extends AdminController {

    /* Defining constants in order to avoid any problem due to typos */
    const FIELD_USR_ID = 'usr_id';
    const FIELD_OLD_PASSWORD = 'usr_old_password';
    const FIELD_NEW_PASSWORD = 'usr_new_password';
    const FIELD_RETYPE_NEW_PASSWORD = 'usr_retype_new_password';

    private $userDao;

    public function __construct() {
        $this->userDao = new UserDao;
    }

    public $get_validation_rules = array( 'id' => 'required|numeric' );
    public $get_filter_rules     = array( 'id' => 'trim' );

    /**
     * @throws GeneralException
     *
     * $this->getParameters['id'] resource key index
     */
    public function getRequest() {
        $this->userDao->setDBH( $this->applicationBuilder->getDbconnection()->getDBH() );
        $user = $this->userDao->getById( $this->getParameters['id'] );

        $this->title = $this->applicationBuilder->getSetup()->getAppNameForPageTitle() . ' :: User edit password';

        $form = new BaseHTMLForm;
        $form->setHtmlTemplateLoader( $this->applicationBuilder->getHtmlTemplateLoader() );
        $form->setTitle( 'User: ' . $user->usr_name . ' ' . $user->usr_surname );
        //$form->addPasswordField(UserEditPassword::FIELD_OLD_PASSWORD, 'Old password:', '6' );
        $form->addPasswordField(UserEditPassword::FIELD_NEW_PASSWORD, 'New password:', '6' );
        $form->addPasswordField(UserEditPassword::FIELD_RETYPE_NEW_PASSWORD, 'Retype new password:', '6' );
        $form->addHiddenField(UserEditPassword::FIELD_USR_ID, $user->usr_id);
        $form->addSubmitButton('save', 'Save');

        $this->menucontainer    = array( new AdminMenu( $this->applicationBuilder->getSetup()->getAppNameForPageTitle(), AdminRouter::ROUTE_ADMIN_USER_LIST ) );
        $this->leftcontainer    = array( new AdminSidebar( $this->applicationBuilder->getSetup()->getAppNameForPageTitle(), AdminRouter::ROUTE_ADMIN_USER_LIST, $this->applicationBuilder->getRouterContainer() ) );
        $this->centralcontainer = array( $form );

        $this->templateFile = $this->applicationBuilder->getSetup()->getPrivateTemplateWithSidebarFileName();
    }

    public $post_validation_rules = array(
        UserEditPassword::FIELD_USR_ID => 'required|numeric',
        //UserEditPassword::FIELD_OLD_PASSWORD => 'required|max_len,100|min_len,6',
        UserEditPassword::FIELD_NEW_PASSWORD => 'required|max_len,100|min_len,6',
        UserEditPassword::FIELD_RETYPE_NEW_PASSWORD => 'required|max_len,100|min_len,6',
    );
    public $post_filter_rules     = array(
        UserEditPassword::FIELD_USR_ID => 'trim',
        //UserEditPassword::FIELD_OLD_PASSWORD => 'trim',
        UserEditPassword::FIELD_NEW_PASSWORD => 'trim',
        UserEditPassword::FIELD_RETYPE_NEW_PASSWORD => 'trim'
    );

    /**
     * @throws GeneralException
     */
    public function postRequest() {
        $this->userDao->setDBH( $this->applicationBuilder->getDbconnection()->getDBH() );

        $user = $this->userDao->getById( $this->postParameters[UserEditPassword::FIELD_USR_ID] );

        /* if ( $this->userDao->checkEmailAndPassword( $user->usr_email, $this->postParameters[UserEditPassword::FIELD_OLD_PASSWORD] ) ) { */
            if ( $this->parameters[UserEditPassword::FIELD_NEW_PASSWORD] == $this->parameters[UserEditPassword::FIELD_RETYPE_NEW_PASSWORD] ) {
                $this->userDao->updatePassword( $this->postParameters[UserEditPassword::FIELD_USR_ID], $this->postParameters[UserEditPassword::FIELD_NEW_PASSWORD]);
                $this->setSuccess("Password successfully updated");
                $this->redirectToSecondPreviousPage();
            } else {
                $this->setError("The two new password do not match");
                $this->redirectToPreviousPage();
            }
        /* } else {
            $this->setError("The old password does not match");
            $this->redirectToPreviousPage();
        } */
    }

    public function show_post_error_page() {
        $this->userDao->setDBH( $this->applicationBuilder->getDbconnection()->getDBH() );
        $user = $this->userDao->getById( $this->getParameters['id'] );

        $this->applicationBuilder->getMessages()->setError($this->readableErrors);

        $this->title = $this->applicationBuilder->getSetup()->getAppNameForPageTitle() . ' :: User edit password';

        $form = new BaseHTMLForm;
        $form->setHtmlTemplateLoader( $this->applicationBuilder->getHtmlTemplateLoader() );
        $form->setTitle( 'User: ' . $user->usr_name . ' ' . $user->usr_surname );
        //$form->addPasswordField(UserEditPassword::FIELD_OLD_PASSWORD, 'Old password:', '6' );
        $form->addPasswordField(UserEditPassword::FIELD_NEW_PASSWORD, 'New password:', '6' );
        $form->addPasswordField(UserEditPassword::FIELD_RETYPE_NEW_PASSWORD, 'Retype new password:', '6' );
        $form->addHiddenField(UserEditPassword::FIELD_USR_ID, $user->usr_id);
        $form->addSubmitButton('save', 'Save');

        $this->menucontainer    = array( new AdminMenu( $this->applicationBuilder->getSetup()->getAppNameForPageTitle(), AdminRouter::ROUTE_ADMIN_USER_LIST ) );
        $this->leftcontainer    = array( new AdminSidebar( $this->applicationBuilder->getSetup()->getAppNameForPageTitle(), AdminRouter::ROUTE_ADMIN_USER_LIST, $this->applicationBuilder->getRouterContainer() ) );
        $this->centralcontainer = array( $form );
    }

}
