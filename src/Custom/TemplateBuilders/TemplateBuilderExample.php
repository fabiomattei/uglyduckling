<?php

namespace Firststep\Custom\TemplateBuilders;

use Firststep\Common\Json\TemplateBuilders\BaseBuilder;
use Firststep\Custom\HTMLBlocks\HTMLBlockExample;

class TemplateBuilderExample extends BaseBuilder {

    const blocktype = 'templatebuilderexample';

    /**
     * @return \Firststep\Common\Blocks\EmptyHTMLBlock|HTMLBlockExample
     */
    public function createHTMLBlock() {
        return new HTMLBlockExample;
    }

}
