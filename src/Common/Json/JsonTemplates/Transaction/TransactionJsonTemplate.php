<?php

/**
 * User: Fabio Mattei
 * Date: 19/09/2020
 * Time: 19:38
 */

namespace Fabiom\UglyDuckling\Common\Json\JsonTemplates\Transaction;

use Fabiom\UglyDuckling\Common\Blocks\MuteHTMLBlock;
use Fabiom\UglyDuckling\Common\Json\JsonTemplates\JsonTemplate;

class TransactionJsonTemplate extends JsonTemplate {

    const blocktype = 'transaction';

    /**
     * Return a object that inherit from BaseHTMLBlock class
     * It is an object that has to generate HTML code
     *
     * @return MuteHTMLBlock
     */
    public function getHTMLBlock() {
        return new MuteHTMLBlock;
    }

}
