<?php

namespace Fabiom\UglyDuckling\Common\Json\JsonTemplates\Uniform;

use Fabiom\UglyDuckling\Common\Blocks\BaseHTMLUniform;
use Fabiom\UglyDuckling\Common\Json\JsonTemplates\JsonTemplate;

/**
 * User: Fabio Mattei
 * Date: 24/05/2020
 * Time: 00:50
 */
class UniformJsonTemplate extends JsonTemplate {

    const blocktype = 'uniform';

    /**
     * Return a object that inherit from BaseHTMLBlock class
     * It is an object that has to generate HTML code
     *
     * @return EmptyHTMLBlock
     */
    public function createHTMLBlock() {
        $htmlTemplateLoader = $this->jsonTemplateFactoriesContainer->getHtmlTemplateLoader();

        $uniformBlock = new BaseHTMLUniform;
        $uniformBlock->setHtmlTemplateLoader($htmlTemplateLoader);
        $uniformBlock->setResourceName( $this->resource->name );
        $uniformBlock->setBodyFile( $this->resource->get->uniform->bodyfile );
        $uniformBlock->setAddToHeadFile( $this->resource->get->uniform->headfile );
        $uniformBlock->setAddToFootFile( $this->resource->get->uniform->footfile );
        $uniformBlock->setAddToHeadOnceFile( $this->resource->get->uniform->headoncefile );
        $uniformBlock->setAddToFootOnceFile( $this->resource->get->uniform->footoncefile );

        return $uniformBlock;
    }

}
