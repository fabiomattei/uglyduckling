<?php

namespace Fabiom\UglyDuckling\Common\Controllers;

class PublicBaseController extends BaseController {

    public function isSessionValid() {
        return true;
    }

}