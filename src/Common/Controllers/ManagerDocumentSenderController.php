<?php

namespace Fabiom\UglyDuckling\Common\Controllers;

use Fabiom\UglyDuckling\Common\Controllers\ManagerEntityController;

/**
 * User: Fabio
 * Date: 07/10/2018
 * Time: 07:53
 *
 * This class inherits all from the parent class, it defines two methods in order to
 * check if a specific user has the credentials to perform the operation is attempting
 * to perform.
 */
class ManagerDocumentSenderController  extends ManagerEntityController {

    /**
     * This method has to be implemented by inerithed class
     * It return true by defult for compatiblity issues
     */
    public function check_authorization_get_request(): bool {
        if( !isset( $this->resource->sourcegroups ) ) return false;
        return in_array( $this->sessionWrapper->getSessionGroup(), $this->resource->sourcegroups );
    }

    /**
     * This method has to be implemented by inerithed class
     * It return true by defult for compatiblity issues
     */
    public function check_authorization_post_request(): bool {
        return in_array( $this->sessionWrapper->getSessionGroup(), $this->resource->sourcegroups );
    }

}
