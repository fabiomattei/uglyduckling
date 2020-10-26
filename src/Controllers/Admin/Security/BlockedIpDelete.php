<?php

/**
 * Created by fabio
 * Date: 26/10/2020
 * Time: 21:21
 */

namespace Fabiom\UglyDuckling\Controllers\Admin\Security;

use Fabiom\UglyDuckling\BusinessLogic\Ip\Daos\IpDao;
use Fabiom\UglyDuckling\Common\Controllers\AdminController;

/**
 * This class gives a list of all entities loaded in to the system
 */
class BlockedIpDelete extends AdminController {

    private /* IpDao */ $ipDao;

    public function __construct() {
        $this->ipDao = new IpDao;
    }

    public $get_validation_rules = array( 'biid' => 'required|numeric' );
    public $get_filter_rules     = array( 'biid' => 'trim' );

    /**
     * @throws GeneralException
     *
     * $this->getParameters['id'] resource key index
     */
    public function getRequest() {
        $this->ipDao->setDBH( $this->pageStatus->getDbconnection()->getDBH() );
        $this->ipDao->delete( $this->getParameters['biid'] );

        $this->redirectToPreviousPage();

        $this->templateFile = $this->applicationBuilder->getSetup()->getPrivateTemplateWithSidebarFileName();
    }

}
