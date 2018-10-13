<?php

namespace Firststep\Common\Controllers;

use Firststep\Common\Controllers\ManagerEntityController;

/**
 * User: Fabio
 * Date: 07/10/2018
 * Time: 07:53
 */
class ManagerDocumentSenderController  extends ManagerEntityController {

    /**
     * This method has to be implemented by inerithed class
     * It return true by defult for compatiblity issues
     */
    public function check_authorization_get_request() {
        if( !isset( $this->resource->sourcegroups ) ) return false;
        return in_array( $this->sessionWrapper->getSessionGroup(), $this->resource->sourcegroups );
    }

    /**
     * This method has to be implemented by inerithed class
     * It return true by defult for compatiblity issues
     */
    public function check_authorization_post_request() {
        return in_array( $this->sessionWrapper->getSessionGroup(), $this->resource->sourcegroups );
    }

}
