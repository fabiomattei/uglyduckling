<?php

namespace Firststep\Custom\HTMLBlocks;

use Firststep\Common\Blocks\BaseHTMLBlock;

class HTMLBlockExample extends BaseHTMLBlock {

    function getHTML(): string {
        return '<p>Paragraph example</p>';
    }

}
