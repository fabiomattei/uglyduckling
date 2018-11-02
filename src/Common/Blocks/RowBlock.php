<?php

/**
 * Created by Fabio Mattei
 * Date: 02/11/2018
 * Time: 04:34
 */

namespace Firststep\Common\Blocks;

use Firststep\Common\Blocks\BaseBlock;

class RowBlock extends BaseBlock {

    private $blocks;

    /**
     * RowBlock constructor.
     * @param $blocks
     */
    public function __construct() {
        $this->blocks = array();
    }

    function addBlock($block) {
        $this->blocks[] = $block;
    }

    function show(): string {
        $body = '<div class="row">';
        foreach ($this->blocks as $bl) {
            $body .= $bl->show();
        }
        $body .= '</div> <!-- end row --!>';
        return $body;
    }

    function addToHead(): string {
        $globalAddToHead = '';
        foreach ($this->blocks as $bl) {
            $globalAddToHead .= $bl->addToHead();
        }
        return $globalAddToHead;
    }

    function addToFoot(): string {
        $globalAddToFoot = '';
        foreach ($this->blocks as $bl) {
            $globalAddToFoot .= $bl->addToFoot();
        }
        return $globalAddToFoot;
    }

}
