<?php

namespace Fabiom\UglyDuckling\Common\Controllers;

class JsonResourceJInPlaceBasicController extends JsonResourceBasicController {

    const CONTROLLER_NAME = 'jinplacecontroller';

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
