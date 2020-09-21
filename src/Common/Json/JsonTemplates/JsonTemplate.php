<?php
/**
 * Created Fabio Mattei
 * Date: 2019-02-10
 * Time: 12:00
 */

namespace Fabiom\UglyDuckling\Common\Json\JsonTemplates;

use Fabiom\UglyDuckling\Common\Blocks\EmptyHTMLBlock;

class JsonTemplate {

    protected $resource;
    protected /* string */ $action;
    protected /* JsonTemplateFactoriesContainer */ $jsonTemplateFactoriesContainer;
    protected /* JsonTemplateFactoriesContainer */ $applicationBuilder;
    protected /* JsonTemplateFactoriesContainer */ $pageStatus;

    const blocktype = 'basebuilder';

    /**
     * BaseBuilder constructor.
     */
    public function __construct( $jsonTemplateFactoriesContainer, $applicationBuilder, $pageStatus ) {
        $this->jsonTemplateFactoriesContainer = $jsonTemplateFactoriesContainer;
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
    public function setAction( string $action ): void {
        $this->action = $action;
    }

    /**
     * Return a object that inherit from BaseHTMLBlock class
     * It is an object that has to generate HTML code
     *
     * @return EmptyHTMLBlock
     */
    public function createHTMLBlock() {
        return new EmptyHTMLBlock;
    }

}
