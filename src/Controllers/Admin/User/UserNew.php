<?php
/**
 * Created by IntelliJ IDEA.
 * User: fabio
 * Date: 21/10/2018
 * Time: 10:39
 */

namespace Fabiom\UglyDuckling\Controllers\Admin\User;

use Fabiom\UglyDuckling\BusinessLogic\Group\Daos\UserGroupDao;
use Fabiom\UglyDuckling\BusinessLogic\User\Daos\UserDao;
use Fabiom\UglyDuckling\Common\Controllers\Controller;
use Fabiom\UglyDuckling\Templates\Blocks\Menus\AdminMenu;
use Fabiom\UglyDuckling\Templates\Blocks\Sidebars\AdminSidebar;
use Fabiom\UglyDuckling\Common\Blocks\BaseHTMLForm;
use Fabiom\UglyDuckling\Common\Router\Router;

/**
 * This class gives a list of all entities loaded in to the system
 */
class UserNew extends Controller {

    private $userDao;
    private $userGroupDao;
    const FIELD_NEW_PASSWORD = 'usr_new_password';
    const FIELD_RETYPE_NEW_PASSWORD = 'usr_retype_new_password';

    public function __construct() {
        $this->userDao = new UserDao;
        $this->userGroupDao = New UserGroupDao();
    }

    /**
     * Overwrite parent showPage method in order to add the functionality of loading a json resource.
     */
    public function showPage() {
        $this->applicationBuilder->getJsonloader()->loadIndex();
        parent::showPage();
    }

    /**
     * @throws GeneralException
     *
     * $this->getParameters['id'] resource key index
     */
    public function getRequest() {
        $this->userDao->setDBH( $this->dbconnection->getDBH() );

        $this->title = $this->applicationBuilder->getSetup()->getAppNameForPageTitle() . ' :: User';

        $form = new BaseHTMLForm;
        $form->setHtmlTemplateLoader( $this->applicationBuilder->getHtmlTemplateLoader() );
        $form->setTitle( 'New user: ' );
        $form->addTextField('usr_email', 'Email: ', 'Email', '', '6' );
        $form->addTextField('usr_name', 'Name: ', 'Name', '', '6' );
        $form->addTextField('usr_surname', 'Surname: ', 'Surname', '', '6' );
        $form->addPasswordField(UserEditPassword::FIELD_NEW_PASSWORD, 'New password:', '6' );
        $form->addPasswordField(UserEditPassword::FIELD_RETYPE_NEW_PASSWORD, 'Retype new password:', '6' );
        $grouparray = array();
        foreach ( $this->applicationBuilder->getJsonloader()->getResourcesByType( 'group' ) as $res ) {
            $grouparray[$res->name] = $res->name;
        }
        $form->addDropdownField('usr_defaultgroup', 'Default Group: ', $grouparray, '', '6' );
        $form->addSubmitButton('save', 'Save');

        $this->menucontainer    = array( new AdminMenu( $this->applicationBuilder->getSetup()->getAppNameForPageTitle(), Router::ROUTE_ADMIN_USER_LIST ) );
        $this->leftcontainer    = array( new AdminSidebar( $this->applicationBuilder->getSetup()->getAppNameForPageTitle(), Router::ROUTE_ADMIN_USER_LIST, $this->applicationBuilder->getRouterContainer() ) );
        $this->centralcontainer = array( $form );

        $this->templateFile = $this->applicationBuilder->getSetup()->getPrivateTemplateWithSidebarFileName();
    }

    public $post_validation_rules = array(
        'usr_email' => 'required|valid_email',
        'usr_name' => 'max_len,100',
        'usr_surname' => 'max_len,100',
        'usr_defaultgroup' => 'max_len,100',
        UserEditPassword::FIELD_NEW_PASSWORD => 'required|max_len,100|min_len,6',
        UserEditPassword::FIELD_RETYPE_NEW_PASSWORD => 'required|max_len,100|min_len,6',
    );
    public $post_filter_rules     = array(
        'usr_email' => 'trim|sanitize_email',
        'usr_name' => 'trim',
        'usr_surname' => 'trim',
        'usr_defaultgroup' => 'trim',
        UserEditPassword::FIELD_NEW_PASSWORD => 'trim',
        UserEditPassword::FIELD_RETYPE_NEW_PASSWORD => 'trim'
    );

    /**
     * @throws GeneralException
     *
     * $this->postParameters['id'] resource key index
     */
    public function postRequest() {
        $this->userDao->setDBH( $this->dbconnection->getDBH() );
        $this->userGroupDao->setDBH( $this->dbconnection->getDBH() );
        if ( $this->parameters[UserEditPassword::FIELD_NEW_PASSWORD] == $this->parameters[UserEditPassword::FIELD_RETYPE_NEW_PASSWORD] ) {
                $iduser = $this->userDao->insert( array(
                    'usr_name' => $this->postParameters['usr_name'],
                    'usr_surname' => $this->postParameters['usr_surname'],
                    'usr_email' => $this->postParameters['usr_email'],
                    'usr_defaultgroup' => $this->postParameters['usr_defaultgroup'],
                    'usr_password_updated' => date('Y-m-d'),
                ) );
                $this->userDao->updatePassword( $iduser, $this->postParameters[UserEditPassword::FIELD_NEW_PASSWORD]);
                $this->userGroupDao->insert(
                    array(
                        'ug_groupslug' => $this->postParameters['usr_defaultgroup'],
                        'ug_userid' => $iduser
                    )
                );
                $this->setSuccess("User successfully saved");
                $this->redirectToSecondPreviousPage();
            } else {
                $this->setError("The two new password do not match");
                $this->redirectToPreviousPage();
            }
        $this->redirectToSecondPreviousPage();
    }

    public function show_post_error_page() {
        $this->userDao->setDBH( $this->dbconnection->getDBH() );

        $this->messages->setError($this->readableErrors);

        $this->title = $this->applicationBuilder->getSetup()->getAppNameForPageTitle() . ' :: User';

        $form = new BaseHTMLForm;
        $form->setHtmlTemplateLoader( $this->applicationBuilder->getHtmlTemplateLoader() );
        $form->setTitle( 'New user: ' );
        $form->addTextField('usr_email', 'Email: ', 'Email', '', '6' );
        $form->addTextField('usr_name', 'Name: ', 'Name', '', '6' );
        $form->addTextField('usr_surname', 'Surname: ', 'Surname', '', '6' );
        $form->addPasswordField(UserEditPassword::FIELD_NEW_PASSWORD, 'New password:', '6' );
        $form->addPasswordField(UserEditPassword::FIELD_RETYPE_NEW_PASSWORD, 'Retype new password:', '6' );
        $grouparray = array();
        foreach ( $this->applicationBuilder->getJsonloader()->getResourcesByType( 'group' ) as $res ) {
            $grouparray[$res->name] = $res->name;
        }
        $form->addDropdownField('usr_defaultgroup', 'Default Group: ', $grouparray, '', '6' );
        $form->addSubmitButton('save', 'Save');

        $this->menucontainer    = array( new AdminMenu( $this->applicationBuilder->getSetup()->getAppNameForPageTitle(), Router::ROUTE_ADMIN_USER_LIST ) );
        $this->leftcontainer    = array( new AdminSidebar( $this->applicationBuilder->getSetup()->getAppNameForPageTitle(), Router::ROUTE_ADMIN_USER_LIST, $this->applicationBuilder->getRouterContainer() ) );
        $this->centralcontainer = array( $form );
    }

}
