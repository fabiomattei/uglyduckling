<?php

namespace Firststep\Custom\JsonTemplates;

use Firststep\Common\Json\JsonTemplates\BaseBuilder;

class CustomTemplateBuildersFactory {

    /**
     * PanelBuilder constructor.
     * @param $tableBuilder
     */
    public function __construct() {
        $this->templateBuilderExample = new TemplateBuilderExample;
    }

    /**
     * Return an HTML Block
     *
     * The HTML block type depends from the resource->metadata->type field in the json strcture
     *
     * @param $resource json strcture
     * @param CardHTMLBlock $panelBlock
     */
    public function getHTMLBlock( $resource ): BaseBuilder {

        if ($resource->metadata->type == TemplateBuilderExample::blocktype) {
            $this->templateBuilderExample->setResource($resource);
            $this->templateBuilderExample->setHtmlTemplateLoader($this->htmlTemplateLoader);
            $this->templateBuilderExample->setJsonloader($this->jsonloader);
            $this->templateBuilderExample->setRouter($this->router);
            $this->templateBuilderExample->setParameters($this->parameters);
            $this->templateBuilderExample->setDbconnection($this->dbconnection);
            return $this->templateBuilderExample->createHTMLBlock();
        }

    }

}
