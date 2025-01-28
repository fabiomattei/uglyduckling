<?php

namespace Fabiom\UglyDuckling\Framework\Json\Parameters\Dashboard;

class JsonResourceParametersGetter implements ParameterGetter {

    protected $resource;

    public function __construct( $resource ) {
        $this->resource = $resource;
    }

    public function getValidationRoules() {
        $rules = array();
        if( isset($this->resource->get->request->parameters) and is_array($this->resource->get->request->parameters) ) {
            foreach ($this->resource->get->request->parameters as $par) {
                $rules[$par->name] = $par->validation;
            }
        }
        return $rules;
    }

    public function getFiltersRoules() {
        $filters = array();
        if( is_array($this->resource->get->request->parameters) ) {
            foreach ($this->resource->get->request->parameters as $par) {
                $filters[$par->name] = 'trim';
            }
        }
        return $filters;
    }

    public function getPostValidationRoules() {
        $rules = array();
        if( is_array($this->resource->post->request->postparameters) ) {
            foreach ($this->resource->post->request->postparameters as $par) {
                $rules[$par->name] = $par->validation;
            }
        }
        return $rules;
    }

    public function getPostFiltersRoules() {
        $filters = array();
        if( is_array($this->resource->post->request->postparameters) ) {
            foreach ($this->resource->post->request->postparameters as $par) {
                $filters[$par->name] = 'trim';
            }
        }
        return $filters;
    }

}
