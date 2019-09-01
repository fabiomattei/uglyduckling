<?php

namespace Fabiom\UglyDuckling\Common\Json\DocBuilders\Table;

use Fabiom\UglyDuckling\Common\Json\DocBuilders\BasicDocBuilder;
use Fabiom\UglyDuckling\Common\Utils\StringUtils;

/**
 *
 */
class TableV1DocBuilder extends BasicDocBuilder {

    public function getDocText() {
        return $this->resource->name.'<br />';
    }

}
