<?php

namespace Firststep\Custom\JsonTemplates;

use Firststep\Common\Json\JsonTemplates\BaseJsonTemplate;
use Firststep\Custom\HTMLBlocks\HTMLBlockExample;

class JsonTemplateExample extends BaseJsonTemplate {

    const blocktype = 'templatebuilderexample';

    /**
     * @return \Firststep\Common\Blocks\EmptyHTMLBlock|HTMLBlockExample
     */
    public function createHTMLBlock() {
        return new HTMLBlockExample;
    }

}
