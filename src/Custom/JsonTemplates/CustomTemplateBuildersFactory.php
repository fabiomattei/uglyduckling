<?php

namespace Firststep\Custom\JsonTemplates;

use Firststep\Common\Json\JsonTemplates\BaseJsonTemplate;

class CustomTemplateBuildersFactory extends BaseJsonTemplate {

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
    public function getHTMLBlock( $resource ): BaseJsonTemplate {

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
