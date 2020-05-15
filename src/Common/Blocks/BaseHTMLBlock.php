<?php

namespace Fabiom\UglyDuckling\Common\Blocks;

use Fabiom\UglyDuckling\Common\Tags\BaseHTMLTag;

/**
 * Class BaseHTMLBlock
 *
 * This class gives the structure to create an HTML block.
 * An HTML block could be a form, a table or a list.
 * Any HTML structure can be formalised as a block.
 *
 */
class BaseHTMLBlock {

    protected /* array */ $tags = array();

    /**
     * @deprecated
     *
     * Overwrite this method with the content you want your block to show
     */
    function show(): string {
        return 'base html block';
    }

    /**
     * Overwrite this method with the content you want your block to show
     *
     * it return the HTML code for the web page
     */
    function getHTML(): string {
        return '';
    }

    /**
     * Overwrite this method with the content you want to put in your html header
     * It is called for every instance of a class.
     * It can be useful if you need to load a css or a javascript file for this block
     * to work properly.
     */
    function addToHead(): string {
        return array_reduce( $this->tags, function ($carry, $tag) { return $carry . ' ' . $tag->addToHead(); }, '' );
    }

    /**
     * Overwrite this method with the content you want to put at the very bottom of your page
     * It can be useful if you need to load a javascript file for this block
     * It is called for every instance of a class.
     */
    function addToFoot(): string {
        return array_reduce( $this->tags, function ($carry, $tag) { return $carry . ' ' . $tag->addToFoot(); }, '' );
    }

    /**
     * Overwrite this method with the content you want to put in your html header
     * It is called only once per class.
     * It can be useful if you need to load a css or a javascript file for this block
     * to work properly.
     */
    function addToHeadOnce(): string {
        return array_reduce( $this->tags, function ($carry, $tag) { return $carry . ' ' . $tag->addToHeadOnce(); }, '' );
    }

    /**
     * Overwrite this method with the content you want to put at the very bottom of your page
     * It can be useful if you need to load a javascript file for this block
     * It is called only once per class.
     */
    function addToFootOnce(): string {
        return array_reduce( $this->tags, function ($carry, $tag) { return $carry . ' ' . $tag->addToFootOnce(); }, '' );
    }

    /**
     * Overwrite this method with the content you want to put in your html header
     * It is called only once per class.
     * It can be useful if you need to load a css or a javascript file for this block
     * to work properly.
     */
    function newAddToHeadOnce(): array {
        return array_reduce( $this->tags, function ($carry, $tag) { return array_merge($carry, $tag->newAddToHeadOnce()); }, [] );
    }

    /**
     * Overwrite this method with the content you want to put at the very bottom of your page
     * It can be useful if you need to load a javascript file for this block
     * It is called only once per class.
     */
    function newAddToFootOnce(): array {
        return array_reduce( $this->tags, function ($carry, $tag) { return array_merge($carry, $tag->newAddToFootOnce() ); }, [] );
    }

    /**
     * Overwrite this method with the content you want to put in your html header
     * It can be useful if you need to write some css or javascript code
     */
    function subAddToHead(): string {
        return '';
    }

    /**
     * Overwrite this method with the content you want to put at the very bottom of your page
     * It can be useful if you need to write some css or javascript code
     */
    function subAddToFoot(): string {
        return '';
    }

    /**
     * Add an HTML Tag to this block
     *
     * @param BaseHTMLTag $tag
     */
    function addHTMLTag( BaseHTMLTag $tag) {
        $this->tags[] = $tag;
    }

}
