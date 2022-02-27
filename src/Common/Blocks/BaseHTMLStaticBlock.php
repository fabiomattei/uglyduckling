<?php

namespace Fabiom\UglyDuckling\Common\Blocks;

use Fabiom\UglyDuckling\Common\Status\ApplicationBuilder;
use Fabiom\UglyDuckling\Common\Status\PageStatus;

class BaseHTMLStaticBlock extends BaseHTMLBlock {

    public ApplicationBuilder $applicationBuilder;
    public PageStatus $pageStatus;

    public function __construct( ApplicationBuilder $applicationBuilder, PageStatus $pageStatus) {
        $this->applicationBuilder = $applicationBuilder;
        $this->pageStatus = $pageStatus;
    }

}