<?php

namespace Fabiom\UglyDuckling\Common\Tags;

use Fabiom\UglyDuckling\Common\Status\PageStatus;
use Fabiom\UglyDuckling\Common\Tags\DefaultTags\HTMLButtonTag;
use Fabiom\UglyDuckling\Common\Tags\DefaultTags\HTMLLinkTag;

class HTMLTagsFactory {

    protected /* array */ $htmlTags;

    private /* TableJsonTemplate */ $htmlButtonTag;
    private /* ChartjsJsonTemplate */ $htmlLinkTag;

    function __construct() {
        $this->htmlTags = array();
        $this->loadDefaults();
    }

    /**
     * This function load the standard HTML tags defined by UD
     */
    function loadDefaults() {
        $this->htmlButtonTag = new HTMLButtonTag;
        $this->htmlLinkTag = new HTMLLinkTag;
        $this->addHTMLTag($this->htmlButtonTag);
        $this->addHTMLTag($this->htmlLinkTag);
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

    public function getHTMLTag( $jsonStructure, PageStatus $pageStatus ): BaseHTMLTag {
        if ( $jsonStructure->type == HTMLButtonTag::BLOCK_TYPE ) {
            new HTMLButtonTag;
            $this->htmlButtonTag->setResources( $jsonStructure, $pageStatus );
            return $this->htmlButtonTag;
        }

        return new BaseHTMLTag;
    }

}
