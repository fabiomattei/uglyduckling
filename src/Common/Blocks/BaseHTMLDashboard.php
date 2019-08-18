<?php

namespace Fabiom\UglyDuckling\Common\Blocks;

/**
 * Class BaseHTMLDashboard
 *
 * A dashboard is basically a container of rows
 */
class BaseHTMLDashboard extends BaseHTMLBloc {

    private $rows;
    private $htmlTemplateLoader;

    /**
     * BaseHTMLDashboard constructor.
     * @param $rows
     */
    public function __construct() {
        $this->rows = array();
    }

    public function setHtmlTemplateLoader($htmlTemplateLoader) {
        $this->htmlTemplateLoader = $htmlTemplateLoader;
    }

    function addBlock($row) {
        $this->rows[] = $row;
    }

    /**
     * Overwrite this method with the content you want your block to show
     *
     * it return the HTML code for the web page
     */
    function getHTML(): string {
        $htmlbody = '';
        foreach ($this->rows as $bl) {
            $htmlbody .= $bl->show();
        }
        return $this->htmlTemplateLoader->loadTemplateAndReplace(
            array( '${htmlbody}' ),
            array( $htmlbody ),
            'RowBlock/body.html');;
    }

    function addToHead(): string {
        $globalAddToHead = '';
        foreach ($this->rows as $bl) {
            $globalAddToHead .= $bl->addToHead();
        }
        return $globalAddToHead;
    }

    function addToFoot(): string {
        $globalAddToFoot = '';
        foreach ($this->rows as $bl) {
            $globalAddToFoot .= $bl->addToFoot();
        }
        return $globalAddToFoot;
    }

}
