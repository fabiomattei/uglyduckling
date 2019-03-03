<?php

/**
 * Created by Fabio Mattei
 * Date: 02/11/2018
 * Time: 04:19
 */

namespace Firststep\Common\Blocks;

use Firststep\Common\Blocks\BaseBlock;
use Firststep\Common\Blocks\EmptyBlock;

class CardBlock extends BaseBlock {

    private $title;
    private $subtitle;
    private $block;
    private $width;
    private $htmlTemplateLoader;

    function __construct() {
        $this->title = '';
        $this->subtitle = '';
        $this->block = new EmptyBlock;
        $this->width = ColWidth::getWidth(ColWidth::MEDIUM, 3);
    }

    public function setHtmlTemplateLoader($htmlTemplateLoader) {
        $this->htmlTemplateLoader = $htmlTemplateLoader;
    }

    function setBlock( $block ) {
        $this->block = $block;
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
            array( '${width}', '${title}', '${subtitle}', '${body}' ),
            array( $this->width, $this->title, $this->showSubTitle(), $this->block->show() ),
            'Card/body.html');
    }

    function addToHead(): string {
        return $this->block->addToHead();
    }

    function addToFoot(): string {
        return $this->block->addToFoot();
    }

}
