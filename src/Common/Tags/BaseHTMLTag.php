<?php

namespace Fabiom\UglyDuckling\Common\Tags;

/**
 * Class BaseHTMLTag
 *
 * This class gives the structure to create an HTML tag.
 * An HTML block could be a form, a table or a list.
 * Any HTML structure can be formalised as a block.
 *
 */
class BaseHTMLTag {

    /**
     * Overwrite this method with the content you want your block to show
     *
     * it return the HTML code for the web page
     * @param $jsonStructure
     * @return string
     */
    function getHTML( $jsonStructure ): string {
        return '';
    }

    /**
     * Overwrite this method with the content you want to put in your html header
     * It is called for every instance of a class.
     * It can be useful if you need to load a css or a javascript file for this block
     * to work properly.
     */
    function addToHead( $jsonStructure ): string {
        return '';
    }

    /**
     * Overwrite this method with the content you want to put at the very bottom of your page
     * It can be useful if you need to load a javascript file for this block
     * It is called for every instance of a class.
     */
    function addToFoot( $jsonStructure ): string {
        return '';
    }

    /**
     * Overwrite this method with the content you want to put in your html header
     * It is called only once per class.
     * It can be useful if you need to load a css or a javascript file for this block
     * to work properly.
     */
    function addToHeadOnce( $jsonStructure ): string {
        return '';
    }

    /**
     * Overwrite this method with the content you want to put at the very bottom of your page
     * It can be useful if you need to load a javascript file for this block
     * It is called only once per class.
     */
    function addToFootOnce( $jsonStructure ): string {
        return '';
    }

}
