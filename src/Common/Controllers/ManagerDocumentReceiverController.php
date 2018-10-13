<?php

namespace Firststep\Common\Controllers;

use Firststep\Common\Controllers\ManagerEntityController;

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
        return in_array( $this->sessionWrapper->getSessionGroup(), $this->resource->destinationgroups );
    }

    /**
     * This method has to be implemented by inerithed class
     * It return true by defult for compatiblity issues
     */
    public function check_authorization_post_request() {
        return in_array( $this->sessionWrapper->getSessionGroup(), $this->resource->destinationgroups );
    }

}
