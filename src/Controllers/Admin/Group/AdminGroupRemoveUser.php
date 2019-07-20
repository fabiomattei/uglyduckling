<?php

/**
 * Created by IntelliJ IDEA.
 * User: fabio
 * Date: 28/10/2018
 * Time: 15:50
 */

namespace Fabiom\UglyDuckling\Controllers\Admin\Group;

use Fabiom\UglyDuckling\BusinessLogic\Group\Daos\UserGroupDao;
use Fabiom\UglyDuckling\Common\Controllers\Controller;

class AdminGroupRemoveUser extends Controller {

    private $userGroupDao;

    public function __construct() {
        $this->userGroupDao = New UserGroupDao();
    }

    public $get_validation_rules = array( 'res' => 'required|max_len,50', 'usrid' => 'required|numeric' );
    public $get_filter_rules     = array( 'res' => 'trim', 'usrid' => 'trim' );

    /**
     * @throws GeneralException
     *
     * $this->getParameters['id'] resource key index
     */
    public function getRequest() {
        $this->userGroupDao->setDBH( $this->dbconnection->getDBH() );

        $this->userGroupDao->deleteByFields( array( 'ug_userid' => $this->getParameters['usrid'], 'ug_groupslug' => $this->getParameters['res'] ) );

        $this->redirectToPreviousPage();
    }

}
