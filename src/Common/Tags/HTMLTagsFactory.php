<?php

namespace Fabiom\UglyDuckling\Common\Tags;

use Fabiom\UglyDuckling\Common\Status\ApplicationBuilder;
use Fabiom\UglyDuckling\Common\Status\PageStatus;
use Fabiom\UglyDuckling\Common\Tags\DefaultTags\HTMLAjaxButtonTag;
use Fabiom\UglyDuckling\Common\Tags\DefaultTags\HTMLButtonTag;
use Fabiom\UglyDuckling\Common\Tags\DefaultTags\HTMLFormButtonTag;
use Fabiom\UglyDuckling\Common\Tags\DefaultTags\HTMLLinkTag;

class HTMLTagsFactory {

    protected /* HTMLButtonTag */ $htmlButtonTag;
    protected /* HTMLLinkTag */ $htmlLinkTag;
    public $htmlAjaxButtonTag;

    /**
     * HTMLTagsFactory constructor.
     * @param $htmlButtonTag
     */
    public function __construct() {
        $this->htmlAjaxButtonTag = new HTMLAjaxButtonTag;
        $this->htmlButtonTag = new HTMLButtonTag;
        $this->htmlLinkTag = new HTMLLinkTag;
    }
    
    /**
     * @param $jsonStructure
     * @param PageStatus $pageStatus
     * @param ApplicationBuilder $applicationBuilder
     * @return BaseHTMLTag
     */
    public function getHTMLTag( $jsonStructure, PageStatus $pageStatus, ApplicationBuilder $applicationBuilder ): string {
        if ( isset($jsonStructure->type) ) {
            if ($jsonStructure->type === HTMLButtonTag::BLOCK_TYPE) {
                $this->htmlButtonTag->setResources($jsonStructure, $pageStatus, $applicationBuilder);
                return $this->htmlButtonTag->getHTML();
            }
            if ($jsonStructure->type === HTMLLinkTag::BLOCK_TYPE) {
                $this->htmlLinkTag->setResources($jsonStructure, $pageStatus, $applicationBuilder);
                return $this->htmlLinkTag->getHTML();
            }
            if ($jsonStructure->type === HTMLFormButtonTag::BLOCK_TYPE) {
                $this->htmlLinkTag->setResources($jsonStructure, $pageStatus, $applicationBuilder);
                return $this->htmlLinkTag->getHTML();
            }
            if ($jsonStructure->type === HTMLAjaxButtonTag::BLOCK_TYPE) {
                $this->htmlAjaxButtonTag->setResources($jsonStructure, $pageStatus, $applicationBuilder);
                return $this->htmlAjaxButtonTag->getHTML();
            }
        }
        return 'undefined tag';
    }

}
