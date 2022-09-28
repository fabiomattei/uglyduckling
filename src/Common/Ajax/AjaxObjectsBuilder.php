<?php

namespace Fabiom\UglyDuckling\Common\Ajax;

class AjaxObjectsBuilder {

    public static function createAjaxObjectForAjaxDeleteObject($type, $destination ) {
        $myAjaxResponse = new \stdClass();
        $myAjaxResponse->type = $type;
        $myAjaxResponse->destination = $destination;
        return $myAjaxResponse;
    }

    public static function createAjaxObjectForAjaxHtmlBlockObject($type, $destination, $position, $body ) {
        $myAjaxResponse = new \stdClass();
        $myAjaxResponse->type = $type;
        $myAjaxResponse->destination = $destination;
        $myAjaxResponse->position = $position;
        $myAjaxResponse->body = $body;
        return $myAjaxResponse;
    }

    public static function createAjaxObjectForAjaxURLBlockObject($type, $destination, $position, $url, $method ) {
        $myAjaxResponse = new \stdClass();
        $myAjaxResponse->type = $type;
        $myAjaxResponse->destination = $destination;
        $myAjaxResponse->position = $position;
        $myAjaxResponse->url = $url;
        $myAjaxResponse->method = $method;
        return $myAjaxResponse;
    }

    public static function createAjaxObjectForAjaxMessageObject($type, $destination, $position, $html ) {
        $myAjaxResponse = new \stdClass();
        $myAjaxResponse->type = $type;
        $myAjaxResponse->destination = $destination;
        $myAjaxResponse->position = $position;
        $myAjaxResponse->html = $html;
        return $myAjaxResponse;
    }

}
