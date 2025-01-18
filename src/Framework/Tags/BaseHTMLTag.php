<?php

/**
 * Created Fabio Mattei
 * Date: 07-05-2020
 * Time: 18:21
 */

namespace Fabiom\UglyDuckling\Framework\Tags;

use Fabiom\UglyDuckling\Framework\Utils\PageStatus;

/**
 * Class BaseHTMLTag
 *
 * This class gives the structure to create an HTML tag.
 * An HTML tag could be a button, a link or any single HTML single tag.
 *
 */
class BaseHTMLTag {

    const BLOCK_TYPE = '';
    protected $jsonStructure;
    protected PageStatus $pageStatus;
    protected array $jsonTagTemplates;

    function __construct($jsonStructure, PageStatus $pageStatus, array $jsonTagTemplates) {
        $this->jsonStructure = $jsonStructure;
        $this->pageStatus = $pageStatus;
        $this->jsonTagTemplates = $jsonTagTemplates;
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
