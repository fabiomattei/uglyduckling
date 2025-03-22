<?php

namespace Fabiom\UglyDuckling\Framework\Json\JsonTemplates\Controller;

use Fabiom\UglyDuckling\Framework\Blocks\BaseHTMLController;
use Fabiom\UglyDuckling\Framework\Json\JsonTemplates\JsonTemplate;

class ControllerJsonTemplate extends JsonTemplate {

    const blocktype = 'controller';

    /**
     * This Json template allows the user to created a structure of file to load.
     * It is meant to be used when there is a javascript app to implant in a web application.
     *
     * En example for a possible structure is the following:
     *
     * {
     *    "name": "moc-list",
     *    "metadata": { "type":"controller", "version": "1" },
     *    "allowedgroups":  [ "managergroup", "basegroup" ],
     *    "get": {
     *       "request": {
     *       "parameters": []
     *     },
     *     "controller": {
     *       "name" : "FabioM\\DEMO\\Chapters\\User\\Controllers\\MyController",
     *       "templateFile": "emptyapptemplate"
     *     }
     *    }
     * }
     *
     * In this case this Json structure tells UD to load the file listed for the appropriate section.
     *
     * @return BaseHTMLController
     */
    public function createHTMLBlock() {
        $controllerBlock = new BaseHTMLController;
        $controllerBlock->setResourceName( $this->resource->name );
        $controllerBlock->setClassName( $this->resource->get->controller->name );
        $controllerBlock->setTemplateFile( $this->resource->get->controller->name );
        $controllerBlock->setPageStatus($this->templateFile);

        return $controllerBlock;
    }

}