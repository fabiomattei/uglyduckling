<?php 

namespace Fabiom\UglyDuckling\Common\Json\DocBuilders\Dashboard;

use Fabiom\UglyDuckling\Common\Json\DocBuilders\BasicDocBuilder;
use Fabiom\UglyDuckling\Common\Utils\StringUtils;

/**
 * 
 */
class DashboardV1DocBuilder extends BasicDocBuilder {

    public function getDocText() {
        $doctext = '';
        foreach ($this->resource->panels as $panel) {
            $tmpres = $this->jsonLoader->loadResource( $panel->resource );
            $docBuilder = BasicDocBuilder::basicJsonDocBuilderFactory( $tmpres, $this->jsonLoader );
            $doctext .= $docBuilder->getDocText();
        }

        return $doctext.'<br />';
    }

}