<?php

namespace Fabiom\UglyDuckling\Common\Controllers;

class JsonResourcePartialBasicController extends ControllerNoCSRFTokenRenew {

    const CONTROLLER_NAME = 'partial';

    /**
     * @throws GeneralException
     */
    public function getRequest() {
        $this->templateFile = 'empty';

        parent::getRequest();
    }

    /**
     * This method implements POST Request logic for all possible json resources.
     * This means all json Resources act in the same way when there is a post request
     */
    public function postRequest() {
        $this->templateFile = 'empty';

        parent::postRequest();
    }

}
