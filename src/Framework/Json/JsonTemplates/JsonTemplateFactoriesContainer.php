<?php

/**
 * Created Fabio Mattei
 * Date: 2019-10-12
 * Time: 22:23
 */

namespace Fabiom\UglyDuckling\Framework\Json\JsonTemplates;

use Fabiom\UglyDuckling\Framework\Blocks\BaseHTMLBlock;

class JsonTemplateFactoriesContainer {

    private /* array */ $factories;
    private /* string */ $action;

    /**
     * JsonTemplateFactoriesContainer constructor.
     */
    public function __construct() {
        $this->factories = array();
    }

    /**
     * Add a factory to the factories container
     * @param $jsonTemplateFactory
     */
    public function addJsonTemplateFactory( $jsonTemplateFactory ) {
        $this->factories[] = $jsonTemplateFactory;
    }

    /**
     * Given a specific json resource select between all JsonTemplateFactories
     * and return an instance of BaseHTMLBlock or a subclass of BaseHTMLBlock
     *
     * @param $jsonResource
     * @return BaseHTMLBlock
     */
    public function getHTMLBlock($jsonResource ): BaseHTMLBlock {
        foreach ($this->factories as $factory) {
            if ( $factory->isResourceSupported( $jsonResource ) ) {
                $factory->setResource( $jsonResource );
                return $factory->getHTMLBlock( $jsonResource );
            }
        }

        return new BaseHTMLBlock;
    }

    /**
     * @return array
     */
    public function getFactories(): array {
        return $this->factories;
    }

}
