<?php

namespace Fabiom\UglyDuckling\Common\Ajax;

class AjaxObjectsBuilder {

    public static function createAjaxObjectForAjaxOutput( $type, $destination, $position, $html ) {
        $myAjaxResponse = new \stdClass();
        $myAjaxResponse->type = $type;
        $myAjaxResponse->destination = $destination;
        $myAjaxResponse->position = $position;
        $myAjaxResponse->html = $html;
        return $myAjaxResponse;
    }

}
