<?php

namespace Fabiom\UglyDuckling\Common\Tags;

use Fabiom\UglyDuckling\Common\Status\ApplicationBuilder;
use Fabiom\UglyDuckling\Common\Status\PageStatus;
use Fabiom\UglyDuckling\Common\Tags\DefaultTags\HTMLButtonTag;
use Fabiom\UglyDuckling\Common\Tags\DefaultTags\HTMLLinkTag;

class HTMLTagsFactory {

    protected /* array */ $htmlTags = array();

    function __construct() {
        $this->htmlTags[HTMLButtonTag::BLOCK_TYPE] = 'HTMLButtonTag';
        $this->htmlTags[HTMLLinkTag::BLOCK_TYPE] = 'HTMLLinkTag';
    }

    /**
     * @param $jsonStructure
     * @param PageStatus $pageStatus
     * @param ApplicationBuilder $applicationBuilder
     * @return BaseHTMLTag
     */
    public function getHTMLTag( $jsonStructure, PageStatus $pageStatus, ApplicationBuilder $applicationBuilder ): BaseHTMLTag {
        if ( key_exists($jsonStructure->type , $this->htmlTags) ) {
            $htmlTag = new $this->htmlTags[$jsonStructure->type];
            $htmlTag->setResources($jsonStructure, $pageStatus, $applicationBuilder);
            return $htmlTag;
        } else {
            return new BaseHTMLTag;
        }
    }

}
