<?php

namespace Fabiom\UglyDuckling\Common\Tags;


class HTMLTagsFactory {

    protected /* array */ $htmlTags;

    function __construct() {
        $this->htmlTags = array();
    }

    function loadDefaults() {
        $this->addHTMLTag(new ButtonBlock);
    }

    /**
     * Add a factory to the factories container
     * @param JsonSmallBlock $htmlTag
     */
    public function addJsonSmallBlock( BaseHTMLTag $htmlTag ) {
        if (array_key_exists($htmlTag::BLOCK_TYPE, $this->htmlTags)) {
            $this->htmlTags[$htmlTag::BLOCK_TYPE] = $htmlTag;
        }
    }



}
