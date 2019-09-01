<?php 

namespace Fabiom\UglyDuckling\Common\Json\DocBuilders\TabbedPage;

use Fabiom\UglyDuckling\Common\Json\DocBuilders\BasicDocBuilder;
use Fabiom\UglyDuckling\Common\Utils\StringUtils;

/**
 * 
 */
class TabbedPageV1DocBuilder extends BasicDocBuilder {

    public function getDocText() {
        return $this->resource->name.'<br />';
    }

}
