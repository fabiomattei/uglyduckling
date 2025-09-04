<?php

namespace Fabiom\UglyDuckling\Framework\Blocks;

/**
 * Class BaseHTMLGrid
 *
 * A grid is basically a container of panels
 */
class BaseHTMLGrid extends BaseHTMLBlock {

    private /* array */ $gridBlocks;
    private /* array */ $gridPanels;
    private string $cssClass;

    /**
     * it return the HTML code for the web page built on data structure
     */
    function getHTML(): string {
        $htmlbody = '<div class="'.$this->cssClass.'">';
        foreach ($this->gridPanels as $panel) {
            if (array_key_exists($panel->id, $this->gridBlocks) AND !is_null($this->gridBlocks[$panel->id])) {
                $id = $panel->id ?? '';
                $cssclass = $panel->cssclass ?? '';
                $htmlbody .= '<div id="'.$id.'" class="'.$cssclass.'">';
                $htmlbody .= $this->gridBlocks[$panel->id]->show();
                $htmlbody .= '</div>';
            } else {
                echo "ERROR: cannot find resource ".$panel->resource." having id ".$panel->id;
            }
        }
        $htmlbody .= '</div>';
        return $htmlbody;
    }

    public function setBlocks($gridBlocks) {
        $this->gridBlocks = $gridBlocks;
    }

    public function setPanels($gridPanels) {
        $this->gridPanels = $gridPanels;
    }

    public function setCssClass($cssClass) {
        $this->cssClass = $cssClass;
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
        foreach ($this->gridPanels as $panel) {
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
        foreach ($this->gridPanels as $panel) {
            $globalAddToFoot .= $this->gridBlocks[$panel->id]->addToFoot();
        }
        return $globalAddToFoot;
    }

    function newAddToHeadOnce(): array {
        $addToHeadDictionary = array();
        foreach ($this->gridPanels as $panel) {
            $addToHeadDictionary = array_merge($addToHeadDictionary, $this->gridBlocks[$panel->id]->newAddToHeadOnce());
        }
        return array_merge( parent::newAddToHeadOnce(), $addToHeadDictionary) ;
    }

    function newAddToFootOnce(): array {
        $addToFootDictionary = array();
        foreach ($this->gridPanels as $panel) {
            $addToFootDictionary = array_merge($addToFootDictionary, $this->gridBlocks[$panel->id]->newAddToFootOnce());
        }
        return array_merge( parent::newAddToFootOnce(), $addToFootDictionary);
    }
}
