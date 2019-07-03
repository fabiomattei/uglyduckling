<?php

namespace Firststep\Custom\TemplateBuilders;

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
    public function getHTMLBlock( $resource ) {

        if ($resource->metadata->type == TemplateBuilderExample::blocktype) {

        }

    }

}
