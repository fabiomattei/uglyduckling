<?php

/**
 * Created by Fabio Mattei
 * Date: 20/03/20
 * Time: 08.31
 */

namespace Fabiom\UglyDuckling\Common\Json\JsonTemplates;

use Fabiom\UglyDuckling\Common\Blocks\EmptyHTMLBlock;

class JsonTemplateWrapper {

    /**
     * Setting JsonTemplateFactoriesContainer
     *
     * @param JsonTemplateFactoriesContainer $jsonTemplateFactoriesContainer
     */
    public function setJsonTemplateFactoriesContainer( JsonTemplateFactoriesContainer $jsonTemplateFactoriesContainer ): void {
        $this->jsonTemplateFactoriesContainer = $jsonTemplateFactoriesContainer;
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
