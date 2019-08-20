<?php

namespace Fabiom\UglyDuckling\Common\Blocks;

/**
 * Class BaseHTMLDashboard
 *
 * A dashboard is basically a container of rows
 */
class BaseHTMLDashboard extends BaseHTMLBlock {

    /**
     * $rows is an array of arrays of BaseHTMLBlock
     * the idea is to put in this structure all BaseHTMLBlock needed in order
     * to create the dashboard and then iterate trough them in order to compose
     * the HTML
     */
    private $rows;

    /**
     * Necessary in order to load the HTML surrounding code
     */
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

	/**
     * Add a BaseHTMLBlock to to current row in data scruture
     */
    function addBlockToCurrentRow( $htmlBlock ) {
        $this->rows[count($this->rows)][] = $htmlBlock;
    }

    /**
     * Add an array ready to recive BaseHTMLBlock to data structure
     */
    function createNewRow() {
        $this->rows[] = array();
    }

    /**
     * it return the HTML code for the web page built on data structure
     */
    function getHTML(): string {
        $htmlbody = '';
        foreach ($this->rows as $row) {
        	$tempHTML = '';
        	foreach ($row as $bl) {
            	$tempHTML .= $bl->show();//$bl->getHTML();
        	}
        	$htmlbody .= $this->htmlTemplateLoader->loadTemplateAndReplace(
                array( '${htmlbody}' ),
                array( $tempHTML ),
            'RowBlock/body.html');
    	}
        return $htmlbody;
    }

    /**
     * Implemented in order to mantain compatibilty with older implementations
     * return HTML code
     * @return string
     */
    function show(): string {
        return $this->getHTML();
    }

    /** 
     * It creates the addToHead string iterating trough all BaseHTMLBlock contained
     * in the data structure
     */
    function addToHead(): string {
        $globalAddToHead = '';
        foreach ($this->rows as $row) {
        	foreach ($row as $bl) {
            	$globalAddToHead .= $bl->addToHead();
        	}
        }
        return $globalAddToHead;
    }

    /** 
     * It creates the addToFoot string iterating trough all BaseHTMLBlock contained
     * in the data structure
     */
    function addToFoot(): string {
        $globalAddToFoot = '';
        foreach ($this->rows as $row) {
        	foreach ($row as $bl) {
            	$globalAddToFoot .= $bl->addToFoot();
        	}
        }
        return $globalAddToFoot;
    }

}
