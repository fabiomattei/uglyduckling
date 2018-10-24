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
class UserEditPassword extends Controller {

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
        $this->userDao->setDBH( $this->dbconnection->getDBH() );
        $user = $this->userDao->getById( $this->getParameters['id'] );

        $this->title = $this->setup->getAppNameForPageTitle() . ' :: User edit password';

        $form = new BaseForm;
        $form->setTitle( 'User: ' . $user->usr_name . ' ' . $user->usr_surname );
        $form->addPasswordField('usr_old_password', 'Old password:', '6' );
        $form->addPasswordField('usr_new_password', 'New password:', '6' );
        $form->addPasswordField('usr_retype_password', 'Retype new password:', '6' );
        $form->addHiddenField('usr_id', $user->usr_id);
        $form->addSubmitButton('save', 'Save');

        $this->menucontainer    = array( new AdminMenu( $this->setup->getAppNameForPageTitle(), Router::ROUTE_ADMIN_USER_LIST ) );
        $this->leftcontainer    = array( new AdminSidebar( $this->setup->getAppNameForPageTitle(), Router::ROUTE_ADMIN_USER_LIST, $this->router ) );
        $this->centralcontainer = array( $form );
    }

    public $post_validation_rules = array(
        'usr_id' => 'required|numeric',
        'usr_old_password' => 'required|max_len,100|min_len,3',
        'usr_new_password' => 'required|max_len,100|min_len,6',
        'usr_retype_password' => 'required|max_len,100|min_len,6',
    );
    public $post_filter_rules     = array(
        'usr_id' => 'trim',
        'usr_old_password' => 'trim',
        'usr_new_password' => 'trim',
        'usr_retype_password' => 'trim'
    );

    /**
     * @throws GeneralException
     */
    public function postRequest() {
        $this->userDao->setDBH( $this->dbconnection->getDBH() );

        $user = $this->userDao->getById( $this->postParameters['usr_id'] );

        if ( $this->userDao->checkEmailAndPassword( $user->usr_email, $this->parameters['usr_old_password'] ) ) {
            if ( $this->parameters['usr_new_password'] == $this->parameters['usr_retype_password'] ) {
                $this->userDao->updatePassword( $this->postParameters['usr_id'], $this->postParameters['usr_new_password']);
                $this->setSuccess("Password successfully updated");
                $this->redirectToSecondPreviousPage();
            } else {
                $this->setError("The two new password do not match");
                $this->redirectToPreviousPage();
            }
        } else {
            $this->setError("The old password do not match");
            $this->redirectToPreviousPage();
        }
    }

    public function show_post_error_page() {
        $this->userDao->setDBH( $this->dbconnection->getDBH() );
        $user = $this->userDao->getById( $this->getParameters['id'] );

        $this->messages->setError($this->readableErrors);

        $this->title = $this->setup->getAppNameForPageTitle() . ' :: User edit password';

        $form = new BaseForm;
        $form->setTitle( 'User: ' . $user->usr_name . ' ' . $user->usr_surname );
        $form->addPasswordField('usr_old_password', 'Old password:', '6' );
        $form->addPasswordField('usr_new_password', 'New password:', '6' );
        $form->addPasswordField('usr_retype_password', 'Retype new password:', '6' );
        $form->addHiddenField('usr_id', $user->usr_id);
        $form->addSubmitButton('save', 'Save');

        $this->menucontainer    = array( new AdminMenu( $this->setup->getAppNameForPageTitle(), Router::ROUTE_ADMIN_USER_LIST ) );
        $this->leftcontainer    = array( new AdminSidebar( $this->setup->getAppNameForPageTitle(), Router::ROUTE_ADMIN_USER_LIST, $this->router ) );
        $this->centralcontainer = array( $form );
    }

}
