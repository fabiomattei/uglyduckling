<?php

namespace Fabiom\UglyDuckling\Common\Blocks;

/**
 * Class BaseHTMLGrid
 *
 * A grid is basically a container of panels
 */
class BaseHTMLGrid extends BaseHTMLBlock {

    private /* array */ $gridBlocks;
    private /* Json Resource */ $resource;

    /**
     * BaseHTMLGrid constructor.
     */
    public function __construct($applicationBuilder, $pageStatus, $resource) {
        $this->applicationBuilder = $applicationBuilder;
        $this->pageStatus = $pageStatus;
        $this->resource = $resource;
        $this->gridBlocks = array();
        foreach ($this->resource->panels as $panel) {
            $this->gridBlocks[$panel->id] = $this->applicationBuilder->getHTMLBlock($this->applicationBuilder->loadResource( $panel->resource ));
        }
    }

    /**
     * it return the HTML code for the web page built on data structure
     */
    function getHTML(): string {
        $htmlbody = '<div class="'.$this->resource->cssclass.'">';
        foreach ($this->resource->panels as $panel) {
            $htmlbody .= '<div class="'.$panel->cssclass.'">';
            $htmlbody .= $this->gridBlocks[$panel->id]->show();
            $htmlbody .= '</div>';
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
        foreach ($this->resource->panels as $panel) {
            $globalAddToHead .= $this->gridBlocks[$panel->id]->addToHead();
        }
        return $globalAddToHead;
    }

    /**
     * It creates the addToFoot string iterating trough all BaseHTMLBlock contained
     * in the data structure
     */
    function addToFoot(): string {
        $globalAddToFoot = '';
        foreach ($this->resource->panels as $panel) {
            $globalAddToFoot .= $this->gridBlocks[$panel->id]->addToFoot();
        }
        return $globalAddToFoot;
    }

    function newAddToHeadOnce(): array {
        $addToHeadDictionary = array();
        foreach ($this->resource->panels as $panel) {
            $addToHeadDictionary = array_merge($addToHeadDictionary, $this->gridBlocks[$panel->id]->newAddToHeadOnce());
        }
        return array_merge( parent::newAddToHeadOnce(), $addToHeadDictionary) ;
    }

    function newAddToFootOnce(): array {
        $addToFootDictionary = array();
        foreach ($this->resource->panels as $panel) {
            $addToFootDictionary = array_merge($addToFootDictionary, $this->gridBlocks[$panel->id]->newAddToFootOnce());
        }
        return array_merge( parent::newAddToFootOnce(), $addToFootDictionary);
    }
}
