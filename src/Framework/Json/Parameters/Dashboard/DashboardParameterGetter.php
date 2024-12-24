<?php

namespace Fabiom\UglyDuckling\Framework\Json\Parameters\Dashboard;

use \Fabiom\UglyDuckling\Common\Json\Parameters\BasicParameterGetter;
use Fabiom\UglyDuckling\Common\Status\ApplicationBuilder;

class DashboardParameterGetter implements ParameterGetter {

    protected $resource;
    protected ApplicationBuilder $applicationBuilder;

    public function __construct($resource, $applicationBuilder) {
        $this->resource = $resource;
        $this->applicationBuilder = $applicationBuilder;
    }

    function getValidationRoules(): array {
        $parameters = array();
        foreach( $this->resource->panels as $panel ) {
            if ($this->applicationBuilder->getJsonloader()->isJsonResourceIndexedAndFileExists( $panel->resource )) {
                $json_resource = $this->applicationBuilder->getJsonloader()->loadResource( $panel->resource );
                $parGetter = BasicParameterGetter::parameterGetterFactory( $json_resource, $this->applicationBuilder );
                $parameters = array_merge( $parameters, $parGetter->getValidationRoules() );
            }
        }
        return $parameters;
    }

    /**
     * Iterates all sub resources contained in a dashboard resource json file
     * in order to return all parameter defined in each of them
     *
     * @return array
     */
    function getFiltersRoules(): array {
        $parameters = array();
        foreach( $this->resource->panels as $panel ) {
            if ($this->applicationBuilder->getJsonloader()->isJsonResourceIndexedAndFileExists( $panel->resource )) {
                $json_resource = $this->applicationBuilder->getJsonloader()->loadResource( $panel->resource );
                $parGetter = BasicParameterGetter::parameterGetterFactory( $json_resource, $this->applicationBuilder );
                $parameters = array_merge( $parameters, $parGetter->getFiltersRoules() );
            }
        }
        return $parameters;
    }

    public function getPostValidationRoules() {
        return [];
    }

    public function getPostFiltersRoules() {
        return [];
    }

}
