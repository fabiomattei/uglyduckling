<?php

/**
 * Created by Fabio Mattei
 * Date: 26/10/2020
 * Time: 21:21
 */

namespace Fabiom\UglyDuckling\Controllers\Admin\Security;

use Fabiom\UglyDuckling\BusinessLogic\Ip\Daos\IpDao;
use Fabiom\UglyDuckling\Common\Controllers\AdminController;
use Fabiom\UglyDuckling\Common\Router\AdminRouter;

/**
 * This class gives a list of all entities loaded in to the system
 */
class BlockedIpDelete extends AdminController {

    private /* IpDao */ $ipDao;

    public function __construct() {
        $this->ipDao = new IpDao;
    }

    public $post_validation_rules = array( 'biid' => 'required|numeric' );
    public $post_filter_rules     = array( 'biid' => 'trim' );

    /**
     * @throws GeneralException
     *
     * $this->postParameters['biid'] resource key index
     */
    public function postRequest() {
        $this->ipDao->setDBH( $this->pageStatus->getDbconnection()->getDBH() );
        $this->ipDao->delete( $this->postParameters['biid'] );

        $this->setSuccess( 'Blocked ip address successfully deleted' );
        $this->redirectToPage( $this->applicationBuilder->getRouterContainer()->makeRelativeUrl( AdminRouter::ROUTE_ADMIN_SECURITY_BLOCKED_IP ) );

        $this->templateFile = $this->applicationBuilder->getSetup()->getPrivateTemplateWithSidebarFileName();
    }

}
