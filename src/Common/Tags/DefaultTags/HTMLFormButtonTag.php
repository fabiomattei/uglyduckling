<?php

/**
 * Created Fabio Mattei
 * Date: 07-11-2020
 * Time: 19:46
 */

namespace Fabiom\UglyDuckling\Common\Tags\DefaultTags;

use Fabiom\UglyDuckling\Common\Status\PageStatus;
use Fabiom\UglyDuckling\Common\Tags\BaseHTMLTag;

/**
 * A Json small block is a JSON resource (object or array or composite)
 * that we need to convert in HTML
 */
class HTMLFormButtonTag extends BaseHTMLTag {

    const BLOCK_TYPE = 'buttonform';

    const COLOR_BLUE              = 'btn-primary';
    const COLOR_GRAY              = 'btn-secondary';
    const COLOR_GREEN             = 'btn-success';
    const COLOR_RED               = 'btn-danger';
    const COLOR_ORANGE            = 'btn-warning';
    const COLOR_MARINE            = 'btn-info';
    const COLOR_LIGHTGRAY         = 'btn-light';
    const COLOR_BLACK             = 'btn-dark';
    const COLOR_EMPTY             = 'btn-link';
    const COLOR_OUTLINE_BLUE      = 'btn-outline-primary';
    const COLOR_OUTLINE_GRAY      = 'btn-outline-secondary';
    const COLOR_OUTLINE_GREEN     = 'btn-outline-success';
    const COLOR_OUTLINE_RED       = 'btn-outline-danger';
    const COLOR_OUTLINE_ORANGE    = 'btn-outline-warning';
    const COLOR_OUTLINE_MARINE    = 'btn-outline-info';
    const COLOR_OUTLINE_LIGHTGRAY = 'btn-outline-light';
    const COLOR_OUTLINE_BLACK     = 'btn-outline-dark';
    const COLOR_OUTLINE_EMPTY     = 'btn-outline-link';
    const LARGE                   = 'btn-lg';
    const SMALL                   = 'btn-sm';
    const BLOCK                   = 'btn-block'; // fill the whole row
    const ACTIVE                  = 'active';
    const DISABLED                = 'disabled';

    /**
     * Takes a JSON resource (object or array or composite) and convert it in HTML
     */
    function getHTML(): string {
        $buttoncolor = ( isset($this->jsonStructure->buttoncolor) ? self::getColor($this->jsonStructure->buttoncolor) : self::COLOR_GRAY );
        $url = $this->applicationBuilder->make_resource_url( $this->jsonStructure, $this->pageStatus );
        return '<form action="'.$url.'" method="POST"><button class="btn '.$buttoncolor.' pull-right m-l-20 btn-rounded ${outline} hidden-xs hidden-sm waves-effect waves-light" ${onclickstring} ${dataoriginaltitle}>'.$this->jsonStructure->label.'</button></form>';
    }

    function getColor($colorcode): string {
        if ($colorcode === "blue" ) {
            return self::COLOR_BLUE;
        }
        if ($colorcode === "gray" ) {
            return self::COLOR_GRAY;
        }
        if ($colorcode === "green" ) {
            return self::COLOR_GREEN;
        }
        if ($colorcode === "red" ) {
            return self::COLOR_RED;
        }
        if ($colorcode === "orange" ) {
            return self::COLOR_ORANGE;
        }
        if ($colorcode === "marine" ) {
            return self::COLOR_MARINE;
        }
        if ($colorcode === "lightgray" ) {
            return self::COLOR_LIGHTGRAY;
        }
        if ($colorcode === "black" ) {
            return self::COLOR_BLACK;
        }
        if ($colorcode === "blank" ) {
            return self::COLOR_EMPTY;
        }
        if ($colorcode === "outline-blue" ) {
            return self::COLOR_OUTLINE_BLUE;
        }
        if ($colorcode === "outline-gray" ) {
            return self::COLOR_OUTLINE_GRAY;
        }
        if ($colorcode === "outline-green" ) {
            return self::COLOR_OUTLINE_GREEN;
        }
        if ($colorcode === "outline-red" ) {
            return self::COLOR_OUTLINE_RED;
        }
        if ($colorcode === "outline-orange" ) {
            return self::COLOR_OUTLINE_ORANGE;
        }
        if ($colorcode === "outline-marine" ) {
            return self::COLOR_OUTLINE_MARINE;
        }
        if ($colorcode === "outline-lightgray" ) {
            return self::COLOR_OUTLINE_LIGHTGRAY;
        }
        if ($colorcode === "outline-black" ) {
            return self::COLOR_OUTLINE_BLACK;
        }
        if ($colorcode === "outline-empty" ) {
            return self::COLOR_OUTLINE_EMPTY;
        }
        return self::COLOR_GRAY;
    }

}
