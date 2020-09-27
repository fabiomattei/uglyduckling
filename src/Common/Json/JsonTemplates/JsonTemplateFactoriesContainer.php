<?php

/**
 * Created Fabio Mattei
 * Date: 2019-10-12
 * Time: 22:23
 */

namespace Fabiom\UglyDuckling\Common\Json\JsonTemplates;

use Fabiom\UglyDuckling\Common\Blocks\BaseHTMLBlock;

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
     * @param $resource
     * @return BaseHTMLBlock
     */
    public function getHTMLBlock( $resource ): BaseHTMLBlock {
        foreach ($this->factories as $factory) {
            if ( $factory->isResourceSupported( $resource ) ) {
                $factory->setResource( $resource );
                return $factory->getHTMLBlock( $resource );
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
