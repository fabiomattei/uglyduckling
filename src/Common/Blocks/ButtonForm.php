<?php

namespace Fabiom\UglyDuckling\Common\Blocks;

use Fabiom\UglyDuckling\Common\Blocks\BaseHTMLBlock;

class ButtonForm extends BaseHTMLBlock {

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
     *
     */
    function get($url, $text, $properties = Button::COLOR_GRAY, $disabled = false) {
        return '<form class="ud-inline-form" action="'.$url.'" method="POST"><button class="btn '.$properties.'" href="" role="button" >'.$text.'</button></form>';
    }

}
