<?php

namespace Fabiom\UglyDuckling\Custom\JsonTemplates;

use Fabiom\UglyDuckling\Common\Json\JsonTemplates\JsonTemplate;

class CustomTemplateBuildersFactory extends JsonTemplate {

    /**
     * PanelBuilder constructor.
     * @param $tableBuilder
     */
    public function __construct() {
        $this->jsonTemplateExample = new JsonTemplateExample;
    }

    /**
     * Return an HTML Block
     *
     * The HTML block type depends from the resource->metadata->type field in the json strcture
     *
     * @param $resource json strcture
     * @param CardHTMLBlock $panelBlock
     */
    public function getHTMLBlock( $resource ): JsonTemplate {

        if ($resource->metadata->type == JsonTemplateExample::blocktype) {
            $this->jsonTemplateExample->setResource($resource);
            $this->jsonTemplateExample->setHtmlTemplateLoader($this->htmlTemplateLoader);
            $this->jsonTemplateExample->setJsonloader($this->jsonloader);
            $this->jsonTemplateExample->setRouter($this->router);
            $this->jsonTemplateExample->setParameters($this->parameters);
            $this->jsonTemplateExample->setDbconnection($this->dbconnection);
            return $this->jsonTemplateExample->createHTMLBlock();
        }

    }

}
