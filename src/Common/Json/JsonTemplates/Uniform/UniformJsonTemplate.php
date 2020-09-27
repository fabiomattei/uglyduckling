<?php

namespace Fabiom\UglyDuckling\Common\Json\JsonTemplates\Uniform;

use Fabiom\UglyDuckling\Common\Blocks\BaseHTMLUniform;
use Fabiom\UglyDuckling\Common\Json\JsonTemplates\JsonTemplate;

/**
 * User: Fabio Mattei
 * Date: 24/05/2020
 * Time: 00:50
 */
class UniformJsonTemplate extends JsonTemplate {

    const blocktype = 'uniform';

    /**
     * This Json template allows the user to created a structure of file to load.
     * It is ment to be used when there is a javascript app to implant in a web application.
     *
     * En example for a possible structure is the following:
     *
     * {
     *   "name": "simplecarassetsnavigationtree",
     *   "metadata": { "type":"uniform", "version": "1" },
     *   "allowedgroups": [ "editor", "author" ],
     *   "get": {
     *     "request": {
     *       "parameters": []
     *     },
     *     "uniform": {
     *       "footfile" : "ZTree/addtofoot.html",
     *       "headfile" : "ZTree/addtohead.html",
     *       "footoncefile" : "",
     *       "headoncefile" : "",
     *       "bodyfile" : "ZTree/body.html"
     *     }
     *   }
     * }
     *
     * In this case this Json structure tells UD to load the file listed for the appropriate section.
     *
     * @return EmptyHTMLBlock
     */
    public function createHTMLBlock() {
        $uniformBlock = new BaseHTMLUniform;
        $uniformBlock->setHtmlTemplateLoader( $this->applicationBuilder->getHtmlTemplateLoader() );
        $uniformBlock->setResourceName( $this->resource->name );
        $uniformBlock->setBodyFile( $this->resource->get->uniform->bodyfile );
        $uniformBlock->setAddToHeadFile( $this->resource->get->uniform->headfile );
        $uniformBlock->setAddToFootFile( $this->resource->get->uniform->footfile );
        $uniformBlock->setAddToHeadOnceFile( $this->resource->get->uniform->headoncefile );
        $uniformBlock->setAddToFootOnceFile( $this->resource->get->uniform->footoncefile );

        return $uniformBlock;
    }

}
