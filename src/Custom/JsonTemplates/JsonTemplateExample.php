<?php

namespace Firststep\Custom\JsonTemplates;

use Firststep\Common\Json\JsonTemplates\BaseBuilder;
use Firststep\Custom\HTMLBlocks\HTMLBlockExample;

class JsonTemplateExample extends BaseBuilder {

    const blocktype = 'templatebuilderexample';

    /**
     * @return \Firststep\Common\Blocks\EmptyHTMLBlock|HTMLBlockExample
     */
    public function createHTMLBlock() {
        return new HTMLBlockExample;
    }

}
