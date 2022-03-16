<?php

namespace Fabiom\UglyDuckling\Common\HTMLStaticBlocks;

use Fabiom\UglyDuckling\Common\Blocks\BaseHTMLBlock;

class HTMLStaticBlockFactory {

    public /* array */ $get_validation_rules = array();
    public /* array */ $get_filter_rules = array();
    public /* array */ $post_validation_rules = array();
    public /* array */ $post_filter_rules = array();

    /**
     * HTMLStaticBlockFactory constructor.
     * @param $applicationBuilder, $pageStatus
     */
    public function __construct( $applicationBuilder, $pageStatus ) {
    }

    public function isHTMLBlockSupported( $htmlBlockName ) {
        return false;
    }

    /**
     * @return array
     */
    public function getGetValidationRules(): array {
        return $this->get_validation_rules;
    }

    /**
     * @return array
     */
    public function getGetFilterRules(): array {
        return $this->get_filter_rules;
    }

    /**
     * @return array
     */
    public function getPostValidationRules(): array {
        return $this->post_validation_rules;
    }

    /**
     * @return array
     */
    public function getPostFilterRules(): array {
        return $this->post_filter_rules;
    }

    /**
     * Return an HTML Block
     *
     * The HTML block type depends from the $blockName parameter
     *
     * @param $blockName string
     */
    public function getHTMLBlock( $htmlBlockName ) {

        return new BaseHTMLBlock;

    }
}