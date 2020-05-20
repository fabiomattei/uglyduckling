<?php

namespace Fabiom\UglyDuckling\Common\Tags;

use Fabiom\UglyDuckling\Common\Status\ApplicationBuilder;
use Fabiom\UglyDuckling\Common\Status\PageStatus;
use Fabiom\UglyDuckling\Common\Tags\DefaultTags\HTMLButtonTag;
use Fabiom\UglyDuckling\Common\Tags\DefaultTags\HTMLLinkTag;

class HTMLTagsFactory {

    protected /* HTMLButtonTag */ $htmlButtonTag;
    protected /* HTMLLinkTag */ $htmlLinkTag;

    /**
     * HTMLTagsFactory constructor.
     * @param $htmlButtonTag
     */
    public function __construct() {
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
            return 'undefined tag';
        }
    }

}
