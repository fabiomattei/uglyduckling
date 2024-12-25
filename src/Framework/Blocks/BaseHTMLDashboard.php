<?php

namespace Fabiom\UglyDuckling\Framework\Blocks;

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
     * BaseHTMLDashboard constructor.
     * @param $rows
     */
    public function __construct() {
        parent::__construct();
        $this->rows = array();
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

    function newAddToHeadOnce(): array {
        $addToHeadDictionary = array();

        foreach ($this->rows as $row) {
            foreach ($row as $block) {
                $addToHeadDictionary = array_merge($addToHeadDictionary, $block->newAddToHeadOnce());
            }
        }

        return array_merge( parent::newAddToHeadOnce(), $addToHeadDictionary) ;
    }

    function newAddToFootOnce(): array {
        $addToFootDictionary = array();

        foreach ($this->rows as $row) {
            foreach ($row as $block) {
                $addToFootDictionary = array_merge($addToFootDictionary, $block->newAddToFootOnce());
            }
        }

        return array_merge( parent::newAddToFootOnce(), $addToFootDictionary);
    }


}
