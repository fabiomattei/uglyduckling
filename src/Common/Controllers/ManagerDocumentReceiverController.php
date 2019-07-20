<?php

namespace Fabiom\UglyDuckling\Common\Controllers;

use Fabiom\UglyDuckling\Common\Controllers\ManagerEntityController;

/**
 * User: Fabio
 * Date: 07/10/2018
 * Time: 07:53
 */
class ManagerDocumentReceiverController  extends ManagerEntityController {

    /**
     * This method has to be implemented by inerithed class
     * It return true by defult for compatiblity issues
     */
    public function check_authorization_get_request() {
        if( !isset( $this->resource->destinationgroups ) ) return false;
        return $this->sessionWrapper->getSessionGroup() === $resource->destinationgroup;
    }

    /**
     * This method has to be implemented by inerithed class
     * It return true by defult for compatiblity issues
     */
    public function check_authorization_post_request() {
        return $this->sessionWrapper->getSessionGroup() === $resource->destinationgroup;
    }

}
