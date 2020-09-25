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

    const blocktype = 'basebuilder';

    /**
     * BaseBuilder constructor.
     */
    public function __construct( $applicationBuilder, $pageStatus ) {
        $this->applicationBuilder = $applicationBuilder;
        $this->pageStatus = $pageStatus;
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