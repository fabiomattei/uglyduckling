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

    function __construct() {
        $this->title = '';
        $this->subtitle = '';
        $this->block = new EmptyBlock;
        $this->width = ColWidth::getWidth(ColWidth::MEDIUM, 3);
    }

    function setBlock( $block ) {
        $this->block = $block;
    }

    function setWidth( $width ) {
        $this->width = $width;
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

    function show(): string {
        return '<div class="card" style="'.$this->width.'">
  <div class="card-body">
    '.($this->title === '' ? '' : '<h5 class="card-title">'.$this->title.'</h5>').'
    '.($this->subtitle === '' ? '' : '<h6 class="card-subtitle mb-2 text-muted">'.$this->subtitle.'</h6>').'
    '.$this->block->show().'
  </div>
</div>';
    }

    function addToHead(): string {
        return $this->block->addToHead();
    }

    function addToFoot(): string {
        return $this->block->addToFoot();
    }

}
