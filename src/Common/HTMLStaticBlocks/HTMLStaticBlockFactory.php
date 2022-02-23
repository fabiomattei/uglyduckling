<?php

namespace Fabiom\UglyDuckling\Common\HTMLStaticBlocks;

use Fabiom\UglyDuckling\Common\Blocks\BaseHTMLBlock;

class HTMLStaticBlockFactory {

    /**
     * HTMLStaticBlockFactory constructor.
     * @param $applicationBuilder, $pageStatus
     */
    public function __construct( $applicationBuilder, $pageStatus ) {
    }

    public function isHTMLBlockSupported( $resource ) {
        return false;
    }

    /**
     * Return an HTML Block
     *
     * The HTML block type depends from the $blockName parameter
     *
     * @param $blockName string
     */
    public function getHTMLBlock( $blockName ) {

        return new BaseHTMLBlock;

    }
}