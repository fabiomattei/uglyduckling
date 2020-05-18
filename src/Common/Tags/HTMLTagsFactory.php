<?php

namespace Fabiom\UglyDuckling\Common\Tags;

use Fabiom\UglyDuckling\Common\Status\ApplicationBuilder;
use Fabiom\UglyDuckling\Common\Status\PageStatus;
use Fabiom\UglyDuckling\Common\Tags\DefaultTags\HTMLButtonTag;
use Fabiom\UglyDuckling\Common\Tags\DefaultTags\HTMLLinkTag;

class HTMLTagsFactory {

    /**
     * @param $jsonStructure
     * @param PageStatus $pageStatus
     * @param ApplicationBuilder $applicationBuilder
     * @return BaseHTMLTag
     */
    public function getHTMLTag( $jsonStructure, PageStatus $pageStatus, ApplicationBuilder $applicationBuilder ): BaseHTMLTag {
        if ( isset($jsonStructure->type) ) {
            if ($jsonStructure->type === HTMLButtonTag::BLOCK_TYPE) {
                $htmlTag = new HTMLButtonTag;
                $htmlTag->setResources($jsonStructure, $pageStatus, $applicationBuilder);
                return $htmlTag;
            }
            if ($jsonStructure->type === HTMLLinkTag::BLOCK_TYPE) {
                $htmlTag = new HTMLLinkTag;
                $htmlTag->setResources($jsonStructure, $pageStatus, $applicationBuilder);
                return $htmlTag;
            }
            return new BaseHTMLTag;
        }
    }

}
