<?php

namespace Fabiom\UglyDuckling\Common\Json\Parameters\Dashboard;

use Fabiom\UglyDuckling\Common\Blocks\BaseHTMLStaticBlock;

class HTMLStaticBlockParametersGetter implements ParameterGetter {

    protected BaseHTMLStaticBlock $resource;

    public function __construct( BaseHTMLStaticBlock $resource ) {
        $this->resource = $resource;
    }

    public function getValidationRoules() {
        return $this->resource->getParametersValidationRules();
    }

    public function getFiltersRoules() {
        return $this->resource->getParametersFilterRules();
    }

    public function getPostValidationRoules() {
        return [];
    }

    public function getPostFiltersRoules() {
        return [];
    }

}
