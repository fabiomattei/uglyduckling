<?php

/**
 * Created Fabio Mattei
 * Date: 2019-10-13
 * Time: 15:20
 */

namespace Fabiom\UglyDuckling\Common\Json\JsonTemplates;

class RoutersContainer {

    private $routers;

    /**
     * JsonTemplateFactoriesContainer constructor.
     */
    public function __construct() {
        $this->routers = array();
    }

    public function getRouter( $resource ) {
        foreach ( $this->routers as $router ) {
            if ( $router->supports( $resource ) ) return $router;
        }
    }
}
