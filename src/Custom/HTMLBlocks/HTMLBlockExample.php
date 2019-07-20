<?php

namespace Fabiom\UglyDuckling\Custom\HTMLBlocks;

use Fabiom\UglyDuckling\Common\Blocks\BaseHTMLBlock;

class HTMLBlockExample extends BaseHTMLBlock {

    function getHTML(): string {
        return '<p>Paragraph example</p>';
    }

}
