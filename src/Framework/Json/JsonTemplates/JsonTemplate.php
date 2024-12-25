<?php
/**
 * Created Fabio Mattei
 * Date: 2019-02-10
 * Time: 12:00
 */

namespace Fabiom\UglyDuckling\Framework\Json\JsonTemplates;

use Fabiom\UglyDuckling\Framework\Blocks\BaseHTMLBlock;
use Fabiom\UglyDuckling\Framework\Blocks\EmptyHTMLBlock;
use Fabiom\UglyDuckling\Framework\Utils\PageStatus;

class JsonTemplate {

    protected $resource;
    protected /* string */ $action;
    protected PageStatus $pageStatus;

    const blocktype = 'basebuilder';
    private array $resourcesIndex;
    private array $tagsIndex;
    private array $jsonResourceTemplates;
    private array $jsonTabTemplates;

    /**
     * BaseBuilder constructor.
     */
    public function __construct( $jsonResource, $pageStatus, $resourcesIndex, $tagsIndex, $jsonResourceTemplates, $jsonTabTemplates ) {
        $this->pageStatus = $pageStatus;
        $this->resourcesIndex = $resourcesIndex;
        $this->tagsIndex = $tagsIndex;
        $this->jsonResourceTemplates = $jsonResourceTemplates;
        $this->jsonTabTemplates = $jsonTabTemplates;
        $this->resource = $jsonResource;
    }

    /**
     * Return a object that inherit from BaseHTMLBlock class
     * It is an object that has to generate HTML code
     *
     * @return BaseHTMLBlock
     */
    public function createHTMLBlock() {
        return new EmptyHTMLBlock;
    }

}
