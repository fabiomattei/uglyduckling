<?php

namespace Fabiom\UglyDuckling\Framework\SmallPartials;

use Fabiom\UglyDuckling\Framework\Utils\PageStatus;

class BaseHTMLSmallPartial
{

    const BLOCK_TYPE = '';
    protected $jsonStructure;
    protected PageStatus $pageStatus;
    protected array $jsonSmallPartialTemplates;

    function __construct($jsonStructure, PageStatus $pageStatus, array $jsonSmallPartialTemplates) {
        $this->jsonStructure = $jsonStructure;
        $this->pageStatus = $pageStatus;
        $this->jsonSmallPartialTemplates = $jsonSmallPartialTemplates;
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

}
