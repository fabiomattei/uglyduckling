<?php

namespace Firststep\Common\Blocks;

class EmptyHTMLBlock extends BaseHTMLBlock {

    function getHTML(): string {
        return '<p>Undefined block</p>';
    }

}
