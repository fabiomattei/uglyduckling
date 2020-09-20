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

    const blocktype = 'basebuilder';

    /**
     * BaseBuilder constructor.
     */
    public function __construct() {

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
     * Setting panelBuilder
     *
     * @param JsonTemplateFactoriesContainer $jsonTemplateFactoriesContainer
     */
    public function setJsonTemplateFactoriesContainer( JsonTemplateFactoriesContainer $jsonTemplateFactoriesContainer ): void {
        $this->jsonTemplateFactoriesContainer = $jsonTemplateFactoriesContainer;
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
