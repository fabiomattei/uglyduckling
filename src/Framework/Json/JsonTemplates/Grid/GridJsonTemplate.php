<?php

/**
 * User: Fabio Mattei
 * Date: 15/10/2020
 * Time: 20:12
 */

namespace Fabiom\UglyDuckling\Framework\Json\JsonTemplates\Grid;

use Fabiom\UglyDuckling\Framework\Json\JsonTemplates\JsonTemplate;
use Fabiom\UglyDuckling\Framework\Blocks\BaseHTMLGrid;

class GridJsonTemplate extends JsonTemplate {

    const blocktype = 'grid';

    /**
     * It creates an HTMLBlock containing all information necessary in order to create
     * the actual HTML code
     *
     * @return BaseHTMLGrid
     */
    public function createHTMLBlock(): BaseHTMLGrid {
        return new BaseHTMLGrid($this->applicationBuilder, $this->pageStatus, $this->resource);
    }

}
