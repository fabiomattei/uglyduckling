<?php

namespace Fabiom\UglyDuckling\Common\Tags;


use Fabiom\UglyDuckling\Common\Tags\DefaultTags\HTMLButtonTag;

class HTMLTagsFactory {

    protected /* array */ $htmlTags;

    function __construct() {
        $this->htmlTags = array();
        $this->loadDefaults();
    }

    /**
     * This function load the standard HTML tags defined by UD
     */
    function loadDefaults() {
        $this->addHTMLTag(new HTMLButtonTag);
    }

    /**
     * This method is ment to be overloaded in order to load custom HTML tags
     */
    function loadCustom() {}

    /**
     * Add an HTMLTag to the factory
     * @param BaseHTMLTag $htmlTag
     */
    public function addHTMLTag( BaseHTMLTag $htmlTag ) {
        if (property_exists(get_class ($htmlTag), 'BLOCK_TYPE')) {
            $this->htmlTags[$htmlTag::BLOCK_TYPE] = $htmlTag;
        }
    }

}
