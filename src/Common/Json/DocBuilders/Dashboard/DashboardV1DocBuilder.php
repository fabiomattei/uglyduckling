<?php 

namespace Fabiom\UglyDuckling\Common\Json\DocBuilders\Dashboard;

use Fabiom\UglyDuckling\Common\Json\DocBuilders\BasicDocBuilder;
use Fabiom\UglyDuckling\Common\Utils\StringUtils;

/**
 * 
 */
class DashboardV1DocBuilder extends BasicDocBuilder {

    public function getDocText() {
        return $this->resource->name.'<br />';
    }

}
