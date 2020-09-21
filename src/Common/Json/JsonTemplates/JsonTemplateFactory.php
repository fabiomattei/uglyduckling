<?php

/**
 * Created by Fabio Mattei
 * Date: 02/11/2018
 * Time: 04:56
 */

namespace Fabiom\UglyDuckling\Common\Json\JsonTemplates;

use Fabiom\UglyDuckling\Common\Json\JsonTemplates\JsonTemplateFactoriesContainer;

class JsonTemplateFactory {

    protected $resource;
    protected $action;
    protected /* JsonTemplateFactoriesContainer */ $jsonTemplateFactoriesContainer;

    const blocktype = 'basebuilder';

    /**
     * @param $container JsonTemplateFactoriesContainer
     */
    public function setJsonTemplateFactoriesContainer( JsonTemplateFactoriesContainer $container ) {
        $this->jsonTemplateFactoriesContainer = $container;
    }

    /**
     * @param mixed $resource
     */
    public function setResource($resource) {
        $this->resource = $resource;
    }

    /**
     * Set the complete URL for the form action
     * @param action $action
     */
    public function setAction( string $action ) {
        $this->action = $action;
    }

}