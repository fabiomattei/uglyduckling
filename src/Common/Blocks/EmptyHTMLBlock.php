<?php

namespace Fabiom\UglyDuckling\Common\Blocks;

class EmptyHTMLBlock extends BaseHTMLBlock {

    function getHTML(): string {
        return '<p>Undefined block</p>';
    }

}
