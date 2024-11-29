<?php

namespace Fabiom\UglyDuckling\Common\Blocks;

use Fabiom\UglyDuckling\Common\Status\ApplicationBuilder;
use Fabiom\UglyDuckling\Common\Status\PageStatus;

/**
 * Class BaseHTMLGrid
 *
 * A grid is basically a container of panels
 */
class BaseHTMLGrid extends BaseHTMLBlock {

    private /* array */ $gridBlocks;
    private /* Json Resource */ $jsonResource;
    public $pageStatus;
    public $applicationBuilder;

    /**
     * BaseHTMLGrid constructor.
     */
    public function __construct(ApplicationBuilder $applicationBuilder, PageStatus $pageStatus, $jsonResource) {
        parent::__construct();
        $this->applicationBuilder = $applicationBuilder;
        $this->pageStatus = $pageStatus;
        $this->jsonResource = $jsonResource;
        $this->gridBlocks = array();
        foreach ($this->jsonResource->panels as $panel) {
            $this->gridBlocks[$panel->id] = $this->applicationBuilder->getHTMLBlock( $this->applicationBuilder->loadResource( $panel->resource ) );
        }
    }

    /**
     * it return the HTML code for the web page built on data structure
     */
    function getHTML(): string {
        $htmlbody = '<div class="'.$this->jsonResource->cssclass.'">';
        foreach ($this->jsonResource->panels as $panel) {
            $id = $panel->id ?? '';
            $cssclass = $panel->cssclass ?? '';
            $htmlbody .= '<div id="'.$id.'" class="'.$cssclass.'">';
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
        foreach ($this->jsonResource->panels as $panel) {
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
        foreach ($this->jsonResource->panels as $panel) {
            $globalAddToFoot .= $this->gridBlocks[$panel->id]->addToFoot();
        }
        return $globalAddToFoot;
    }

    function newAddToHeadOnce(): array {
        $addToHeadDictionary = array();
        foreach ($this->jsonResource->panels as $panel) {
            $addToHeadDictionary = array_merge($addToHeadDictionary, $this->gridBlocks[$panel->id]->newAddToHeadOnce());
        }
        return array_merge( parent::newAddToHeadOnce(), $addToHeadDictionary) ;
    }

    function newAddToFootOnce(): array {
        $addToFootDictionary = array();
        foreach ($this->jsonResource->panels as $panel) {
            $addToFootDictionary = array_merge($addToFootDictionary, $this->gridBlocks[$panel->id]->newAddToFootOnce());
        }
        return array_merge( parent::newAddToFootOnce(), $addToFootDictionary);
    }
}
