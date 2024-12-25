<?php

/**
 * Created by Fabio Mattei
 * Date: 02/11/2018
 * Time: 04:19
 */

namespace Fabiom\UglyDuckling\Framework\Blocks;

class CardHTMLBlock extends BaseHTMLBlock {

    private $title;
    private $subtitle;
    private $block;
    private $width;
	private $cssClass;
    private $htmlTemplateLoader;
    private /* string */ $cardExternalContainerId;
	private /* string */ $cardId;

    function __construct() {
        parent::__construct();
        $this->title = '';
        $this->subtitle = '';
        $this->cardExternalContainerId = '';
        $this->cardId = '';
		$this->cssClass = '';
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
    function setInternalBlockName( $id ) {
        $this->cardId = $id;
    }

    function setWidth( int $width ) {
        $this->width = ColWidth::getWidth(ColWidth::MEDIUM, $width);
    }
	
    function setCssClass( string $cssClass ) {
		if ( $cssClass !== '' ) {
			$this->width = $cssClass;
		}
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
         return ( $this->subtitle === '' ? '' : HtmlTemplateLoader::loadTemplateAndReplace(TEMPLATES_PATH, 
            array( '${subtitle}' ),
            array( $this->subtitle ),
            'Card/subtitle.html') );
    }

    function show(): string {
        return HtmlTemplateLoader::loadTemplateAndReplace(TEMPLATES_PATH, 
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

    function newAddToHeadOnce(): array {
        return $this->block->newAddToHeadOnce();
    }

    function newAddToFootOnce(): array {
        return $this->block->newAddToFootOnce();
    }

}
