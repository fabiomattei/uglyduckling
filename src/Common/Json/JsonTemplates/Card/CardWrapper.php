<?php

/**
 * Created by Fabio Mattei
 * Date: 20/03/20
 * Time: 08.16
 */

namespace Fabiom\UglyDuckling\Common\Json\JsonTemplates\Chartjs;

use Fabiom\UglyDuckling\Common\Blocks\EmptyHTMLBlock;
use Fabiom\UglyDuckling\Common\Json\JsonTemplates\JsonTemplateWrapper;

class CardWrapper extends JsonTemplateWrapper {

    const blocktype = 'card';

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