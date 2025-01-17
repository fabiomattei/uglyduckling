<?php

namespace Fabiom\UglyDuckling\Framework\Json\Parameters\Dashboard;

use Fabiom\UglyDuckling\Framework\Json\JsonLoader;
use Fabiom\UglyDuckling\Framework\Json\Parameters\BasicParameterGetter;

class DashboardParameterGetter implements ParameterGetter {

    protected $resource;
    protected array $resourceIndex;

    public function __construct($resource, $resourceIndex) {
        $this->resource = $resource;
        $this->resourceIndex = $resourceIndex;
    }

    function getValidationRoules(): array {
        $parameters = array();
        foreach( $this->resource->panels as $panel ) {
            if (JsonLoader::isJsonResourceIndexedAndFileExists( $this->resourceIndex, $panel->resource )) {
                $json_resource = JsonLoader::loadResource( $this->resourceIndex, $panel->resource );
                $parGetter = BasicParameterGetter::parameterGetterFactory( $json_resource, $this->resourceIndex );
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
            if (JsonLoader::isJsonResourceIndexedAndFileExists( $this->resourceIndex, $panel->resource )) {
                $json_resource = JsonLoader::loadResource( $this->resourceIndex, $panel->resource );
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
