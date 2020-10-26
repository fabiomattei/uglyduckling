<?php

/**
 * Created by fabio
 * Date: 26/10/2020
 * Time: 21:21
 */

namespace Fabiom\UglyDuckling\Controllers\Admin\Security;

use Fabiom\UglyDuckling\BusinessLogic\Ip\Daos\DeactivatedUserDao;
use Fabiom\UglyDuckling\Common\Controllers\AdminController;

/**
 * This class gives a list of all entities loaded in to the system
 */
class DeactivatedUserDelete extends AdminController {

    private /* DeactivatedUserDao */ $deactivatedUserDao;

    public function __construct() {
        $this->deactivatedUserDao = new DeactivatedUserDao;
    }

    public $get_validation_rules = array( 'duid' => 'required|numeric' );
    public $get_filter_rules     = array( 'duid' => 'trim' );

    /**
     * @throws GeneralException
     *
     * $this->getParameters['id'] resource key index
     */
    public function getRequest() {
        $this->deactivatedUserDao->setDBH( $this->pageStatus->getDbconnection()->getDBH() );
        $this->deactivatedUserDao->delete( $this->getParameters['duid'] );

        $this->redirectToPreviousPage();

        $this->templateFile = $this->applicationBuilder->getSetup()->getPrivateTemplateWithSidebarFileName();
    }

}
