<?php

namespace Firststep\Common\Blocks;

/**
 * Class BaseHTMLBlock
 * @package Firststep\Common\Blocks
 *
 * This class gives the structure to create an HTML block.
 * An HTML block could be a form, a table or a list.
 * Any HTML structure can be formalised as a block.
 *
 */
abstract class BaseHTMLBlock {

    /**
     * @deprecated
     *
     * Overwrite this method with the content you want your block to show
     */
    function show(): string {
        return '';
    }

    /**
     * Overwrite this method with the content you want your block to show
     *
     * it return the HTML code for the web page
     */
    function getHTML(): string {
        return '';
    }

    /**
     * Overwrite this method with the content you want to put in your html header
     * It can be useful if you need to load a css or a javascript file for this block
     * to work properly.
     */
    function addToHead(): string {
        return '';
    }

    /**
     * Overwrite this method with the content you want to put at the very bottom of your page
     * It can be useful if you need to load a javascript file for this block
     */
    function addToFoot(): string {
        return '';
    }

    /**
     * Overwrite this method with the content you want to put in your html header
     * It can be useful if you need to write some css or javascript code
     */
    function subAddToHead(): string {
        return '';
    }

    /**
     * Overwrite this method with the content you want to put at the very bottom of your page
     * It can be useful if you need to write some css or javascript code
     */
    function subAddToFoot(): string {
        return '';
    }

}
