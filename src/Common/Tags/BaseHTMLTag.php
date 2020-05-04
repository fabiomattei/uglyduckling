<?php

namespace Fabiom\UglyDuckling\Common\Tags;

use Fabiom\UglyDuckling\Common\Status\PageStatus;

/**
 * Class BaseHTMLTag
 *
 * This class gives the structure to create an HTML tag.
 * An HTML tag could be a button, a link or any single HTML single tag.
 *
 */
class BaseHTMLTag {

    const tagtype = 'basetag';
    protected $jsonStructure;
    protected /* PageStatus */ $pageStatus;

    function __construct($jsonStructure, PageStatus $pageStatus) {
        $this->jsonStructure = $jsonStructure;
        $this->pageStatus = $pageStatus;
    }

    /**
     * Overwrite this method with the content you want your block to show
     *
     * it return the HTML code for the web page
     * @param $jsonStructure
     * @return string
     */
    function getHTML(): string {
        return '';
    }

    /**
     * Overwrite this method with the content you want to put in your html header
     * It is called for every instance of a class.
     * It can be useful if you need to load a css or a javascript file for this block
     * to work properly.
     */
    function addToHead(): string {
        return '';
    }

    /**
     * Overwrite this method with the content you want to put at the very bottom of your page
     * It can be useful if you need to load a javascript file for this block
     * It is called for every instance of a class.
     */
    function addToFoot(): string {
        return '';
    }

    /**
     * Overwrite this method with the content you want to put in your html header
     * It is called only once per class.
     * It can be useful if you need to load a css or a javascript file for this block
     * to work properly.
     */
    function addToHeadOnce(): string {
        return '';
    }

    /**
     * Overwrite this method with the content you want to put at the very bottom of your page
     * It can be useful if you need to load a javascript file for this block
     * It is called only once per class.
     */
    function addToFootOnce(): string {
        return '';
    }

}
