<?php

namespace Fabiom\UglyDuckling\Common\Tags;

use Fabiom\UglyDuckling\Common\Status\ApplicationBuilder;
use Fabiom\UglyDuckling\Common\Status\PageStatus;
use Fabiom\UglyDuckling\Common\Tags\DefaultTags\HTMLButtonTag;
use Fabiom\UglyDuckling\Common\Tags\DefaultTags\HTMLLinkTag;

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
        $this->htmlButtonTag[HTMLButtonTag::BLOCK_TYPE] = new HTMLButtonTag;
        $this->htmlLinkTag[HTMLLinkTag::BLOCK_TYPE] = new HTMLLinkTag;
    }

    /**
     * This method is ment to be everrided in order to load custom HTML tags
     */
    function loadCustom() {}

    /**
     * @param $jsonStructure
     * @param PageStatus $pageStatus
     * @param ApplicationBuilder $applicationBuilder
     * @return BaseHTMLTag
     */
    public function getHTMLTag( $jsonStructure, PageStatus $pageStatus, ApplicationBuilder $applicationBuilder ): BaseHTMLTag {
        if ( key_exists($jsonStructure->type , $this->htmlTags) ) {
            $htmlTag = $this->htmlTags[$jsonStructure->type];
            $htmlTag->setResources($jsonStructure, $pageStatus);
            return $htmlTag;
        } else {
            return new BaseHTMLTag;
        }
    }

}
