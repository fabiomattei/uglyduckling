<?php

/**
 * Created by Fabio Mattei
 * Date: 26/10/2020
 * Time: 21:21
 */

namespace Fabiom\UglyDuckling\Controllers\Admin\Security;

use Fabiom\UglyDuckling\BusinessLogic\Ip\Daos\DeactivatedUserDao;
use Fabiom\UglyDuckling\Common\Controllers\AdminController;
use Fabiom\UglyDuckling\Common\Router\AdminRouter;

/**
 * This class gives a list of all entities loaded in to the system
 */
class DeactivatedUserDelete extends AdminController {

    private /* DeactivatedUserDao */ $deactivatedUserDao;

    public function __construct() {
        $this->deactivatedUserDao = new DeactivatedUserDao;
    }

    public $post_validation_rules = array( 'duid' => 'required|numeric' );
    public $post_filter_rules     = array( 'duid' => 'trim' );

    /**
     * @throws GeneralException
     *
     * $this->postParameters['duid'] resource key index
     */
    public function postRequest() {
        $this->deactivatedUserDao->setDBH( $this->pageStatus->getDbconnection()->getDBH() );
        $this->deactivatedUserDao->delete( $this->postParameters['duid'] );

        $this->setSuccess( 'Blocked User successfully deleted' );
        $this->redirectToPage( $this->applicationBuilder->getRouterContainer()->makeRelativeUrl( AdminRouter::ROUTE_ADMIN_SECURITY_DEACTIVATED_USER ) );

        $this->templateFile = $this->applicationBuilder->getSetup()->getPrivateTemplateWithSidebarFileName();
    }

}
