<?php

/**
 * Created Fabio Mattei
 * Date: 2019-10-12
 * Time: 22:23
 */

namespace Fabiom\UglyDuckling\Common\Json\JsonTemplates;

class JsonTemplateFactoriesContainer {

    private $factories;

    /**
     * JsonTemplateFactoriesContainer constructor.
     */
    public function __construct() {
        $this->factories = array();
    }

    public function addJsonTemplateFactory( $jsonTemplateFactory ) {
        $this->factories[] = $jsonTemplateFactory;
    }

    public function getHTMLBlock( $resource ) {
        foreach ($this->factories as $factory) {
            if ($factory->isResourceSupported( $resource )) {
                return $factory->getHTMLBlock( $resource );
            }
        }
    }

}