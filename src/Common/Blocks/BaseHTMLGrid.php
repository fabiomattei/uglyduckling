<?php

namespace Fabiom\UglyDuckling\Common\Blocks;

/**
 * Class BaseHTMLGrid
 *
 * A grid is basically a container of panels
 */
class BaseHTMLGrid extends BaseHTMLBlock {

    private /* array */ $htmlBlocks;

    /**
     * BaseHTMLGrid constructor.
     */
    public function __construct($applicationBuilder, $pageStatus, $resource) {
        $this->applicationBuilder = $applicationBuilder;
        $this->pageStatus = $pageStatus;
        $this->resource = $resource;
        $this->htmlBlocks = array();
    }

    /**
     * it return the HTML code for the web page built on data structure
     */
    function getHTML(): string {
        $htmlbody = '<div class="'.$this->resource->cssclass.'">';
        foreach ($this->resource->panels as $panel) {
            $resource = $this->applicationBuilder->loadResource( $panel->resource );
            $this->htmlBlocks[] = $this->applicationBuilder->getHTMLBlock($resource);
        }
        $htmlbody .= '</div>';
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
