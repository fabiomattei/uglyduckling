<?php

namespace Fabiom\UglyDuckling\Common\Controllers;

class PublicBaseController extends BaseController {

    private function isSessionValid() {
        return true;
    }

}