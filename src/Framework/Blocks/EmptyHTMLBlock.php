<?php

namespace Fabiom\UglyDuckling\Framework\Blocks;

class EmptyHTMLBlock extends BaseHTMLBlock {

    function getHTML(): string {
        return '<p>Undefined block</p>';
    }

}
