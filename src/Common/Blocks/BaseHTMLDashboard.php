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
     * Add a BaseHTMLBlock to to current row in data structure
     *
     * @param BaseHTMLBlock structure $htmlBlock
     */
    function addBlockToCurrentRow( BaseHTMLBlock $htmlBlock ) {
        $this->rows[count($this->rows) - 1][] = $htmlBlock;
    }

    /**
     * Add an array ready to receive BaseHTMLBlock to data structure
     */
    function createNewRow() {
        $this->rows[count($this->rows)] = array();
    }

    /**
     * it return the HTML code for the web page built on data structure
     */
    function getHTML(): string {
        $htmlbody = '';
        foreach ($this->rows as $row) {
            $rowBlock = new RowHTMLBlock;
            $rowBlock->setHtmlTemplateLoader( $this->htmlTemplateLoader );
            foreach ($row as $panel) {
                $rowBlock->addBlock( $panel );
        	}

            $htmlbody .= $rowBlock->show();//$bl->getHTML();
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
        	foreach ($row as $block) {
            	$globalAddToHead .= $block->addToHead();
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
        	foreach ($row as $block) {
            	$globalAddToFoot .= $block->addToFoot();
        	}
        }
        
        return $globalAddToFoot;
    }

    /**
     * Overwrite this method with the content you want to put in your html header
     * It is called only once per class.
     * It can be useful if you need to load a css or a javascript file for this block
     * to work properly.
     */
    function addToHeadOnce(): string {
        $addToHeadDictionary = array();
        
        foreach ($this->rows as $row) {
            foreach ($row as $block) {
                $addToHeadDictionary[get_class($block)] = $block->addToFootOnce();
            }
        }

        foreach ($addToHeadDictionary as $htmlBlock) {
            $globalAddToHead .= $htmlBlock;
        }

        return $globalAddToHead;
    }

    /**
     * Overwrite this method with the content you want to put at the very bottom of your page
     * It can be useful if you need to load a javascript file for this block
     * It is called only once per class.
     */
    function addToFootOnce(): string {
        $addToFootDictionary = array();
        $globalAddToFoot = '';
        
        foreach ($this->rows as $row) {
            foreach ($row as $block) {
                $addToFootDictionary[get_class($block)] = $block->addToHeadOnce();
            }
        }

        foreach ($addToFootDictionary as $htmlBlock) {
            $globalAddToFoot .= $htmlBlock;
        }
        
        return $globalAddToFoot;
    }

}
