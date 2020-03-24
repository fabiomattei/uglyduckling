<?php

/**
 * Created by Fabio Mattei
 * Date: 02/11/2018
 * Time: 04:19
 */

namespace Fabiom\UglyDuckling\Common\Blocks;

use Fabiom\UglyDuckling\Common\Blocks\BaseHTMLBlock;
use Fabiom\UglyDuckling\Common\Blocks\EmptyHTMLBlock;

class CardHTMLBlock extends BaseHTMLBlock {

    private $title;
    private $subtitle;
    private $block;
    private $width;
    private $htmlTemplateLoader;
    private /* string */ $cardExternalContainerId;
	private /* string */ $cardId;

    function __construct() {
        $this->title = '';
        $this->subtitle = '';
        $this->cardExternalContainerId = '';
        $this->cardId = '';
        $this->block = new EmptyHTMLBlock;
        $this->width = ColWidth::getWidth(ColWidth::MEDIUM, 3);
    }

    public function setHtmlTemplateLoader($htmlTemplateLoader) {
        $this->htmlTemplateLoader = $htmlTemplateLoader;
    }

    function setBlock( $block ) {
        $this->block = $block;
    }

    /**
     * Set block name in odre to generate CARDBLOCK External ID and internal ID
     *
     * @param $name
     */
    function setInternalBlockName( $name ) {
        $this->cardExternalContainerId = 'externalcalrdcontainer-'.$name;
        $this->cardId = 'cardcontainer-'.$name;
    }

    function setWidth( int $width ) {
        $this->width = ColWidth::getWidth(ColWidth::MEDIUM, $width);
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title) {
        $this->title = $title;
    }

    /**
     * @param string $subtitle
     */
    public function setSubtitle(string $subtitle) {
        $this->subtitle = $subtitle;
    }

    public function showSubTitle(): string {
         return ( $this->subtitle === '' ? '' : $this->htmlTemplateLoader->loadTemplateAndReplace(
            array( '${subtitle}' ),
            array( $this->subtitle ),
            'Card/subtitle.html') );
    }

    function show(): string {
        return $this->htmlTemplateLoader->loadTemplateAndReplace(
            array( '${width}', '${title}', '${subtitle}', '${body}', '${cardExternalContainerId}', '${cardId}' ),
            array( $this->width, $this->title, $this->showSubTitle(), $this->block->show(), $this->cardExternalContainerId, $this->cardId ),
            'Card/body.html');
    }

    function addToHead(): string {
        return $this->block->addToHead();
    }

    function addToFoot(): string {
        return $this->block->addToFoot();
    }

    function addToHeadOnce(): string {
        return $this->block->addToHeadOnce();
    }

    function addToFootOnce(): string {
        return $this->block->addToFootOnce();
    }

}
