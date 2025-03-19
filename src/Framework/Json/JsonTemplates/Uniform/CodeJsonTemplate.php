<?php

namespace Fabiom\UglyDuckling\Framework\Json\JsonTemplates\Uniform;

use Fabiom\UglyDuckling\Framework\Blocks\BaseHTMLCode;
use Fabiom\UglyDuckling\Framework\Json\JsonTemplates\JsonTemplate;

/**
 * User: Fabio Mattei
 * Date: 24/05/2020
 * Time: 00:50
 */
class CodeJsonTemplate extends JsonTemplate {

    const blocktype = 'code';

    /**
     * This Json template allows the user to created a structure of file to load.
     * It is meant to be used when there is a javascript app to implant in a web application.
     *
     * En example for a possible structure is the following:
     *
     * {
     *   "name": "simplecodeblock",
     *   "metadata": { "type":"code", "version": "1" },
     *   "allowedgroups": [ "editor", "author" ],
     *   "get": {
     *     "request": {
     *       "parameters": []
     *     },
     *     "code": {
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
     * @return BaseHTMLCode
     */
    public function createHTMLBlock() {
        $codeBlock = new BaseHTMLCode;
        $codeBlock->setResourceName( $this->resource->name );
        $codeBlock->setBodyFile( $this->resource->get->code->bodyfile );
        $codeBlock->setAddToHeadFile( $this->resource->get->code->headfile );
        $codeBlock->setAddToFootFile( $this->resource->get->code->footfile );
        $codeBlock->setAddToHeadOnceFile( $this->resource->get->code->headoncefile );
        $codeBlock->setAddToFootOnceFile( $this->resource->get->code->footoncefile );

        return code;
    }

}
