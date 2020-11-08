<?php

/**
 * Created by IntelliJ IDEA.
 * User: fabio
 * Date: 28/10/2018
 * Time: 15:46
 */

namespace Fabiom\UglyDuckling\Controllers\Admin\User;

use Fabiom\UglyDuckling\BusinessLogic\Group\Daos\UserGroupDao;
use Fabiom\UglyDuckling\BusinessLogic\User\Daos\UserDao;
use Fabiom\UglyDuckling\Common\Controllers\AdminController;

/**
 * This class gives a list of all entities loaded in to the system
 */
class UserDelete extends AdminController {

    private $userDao;
    private $userGroupDao;

    public function __construct() {
        $this->userDao = new UserDao;
        $this->userGroupDao = New UserGroupDao();
    }

    public $post_validation_rules = array( 'usrid' => 'required|numeric' );
    public $post_filter_rules     = array( 'usrid' => 'trim' );

    /**
     * @throws GeneralException
     *
     * $this->getParameters['id'] resource key index
     */
    public function postRequest() {
        $this->userDao->setDBH( $this->pageStatus->getDbconnection()->getDBH() );
        $this->userGroupDao->setDBH( $this->pageStatus->getDbconnection()->getDBH() );

        $this->userGroupDao->deleteByFields( array( 'ug_userid' => $this->postParameters['usrid'] ) );
        $this->userDao->delete( $this->postParameters['usrid'] );

        $this->setSuccess( 'User successfully deleted' );

        $this->redirectToPage( $this->applicationBuilder->getRouter()->makeRelativeUrl( AdminRouter::ROUTE_ADMIN_GROUP_LIST ) );

        $this->templateFile = $this->applicationBuilder->getSetup()->getPrivateTemplateWithSidebarFileName();
    }

}
