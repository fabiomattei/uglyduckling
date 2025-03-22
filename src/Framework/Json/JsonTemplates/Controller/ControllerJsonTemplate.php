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
        $codeBlock = new BaseHTMLController;
        $codeBlock->setPageStatus($this->pageStatus);

        return $codeBlock;
    }

}