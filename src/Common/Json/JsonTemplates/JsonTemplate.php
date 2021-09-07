<?php
/**
 * Created Fabio Mattei
 * Date: 2019-02-10
 * Time: 12:00
 */

namespace Fabiom\UglyDuckling\Common\Json\JsonTemplates;

use Fabiom\UglyDuckling\Common\Blocks\EmptyHTMLBlock;
use Fabiom\UglyDuckling\Common\Status\ApplicationBuilder;
use Fabiom\UglyDuckling\Common\Status\PageStatus;

class JsonTemplate {

    protected $resource;
    protected /* string */ $action;
    protected ApplicationBuilder $applicationBuilder;
    protected PageStatus $pageStatus;

    const blocktype = 'basebuilder';

    /**
     * BaseBuilder constructor.
     */
    public function __construct( $applicationBuilder, $pageStatus ) {
        $this->applicationBuilder = $applicationBuilder;
        $this->pageStatus = $pageStatus;
    }

    /**
     * @param mixed $resource
     */
    public function setResource( $resource ) {
        $this->resource = $resource;
    }

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
