<?php

namespace Firststep\Common\Json\Parameters\Dashboard;

use \Firststep\Common\Json\Parameters\BasicParameterGetter;

class DashboardParameterGetter extends BasicParameterGetter {

    function getGetParameters(): array {
        $parameters = array();
        foreach( $this->resource->panels as $panel ) {
            $json_resource = $this->jsonloader->loadResource( $panel->resource );
            $parGetter = BasicParameterGetter::basicParameterCheckerFactory( $json_resource, $this->jsonloader );
            $parameters = array_merge( $parameters, $parGetter->getGetParameters() );
        }
        return $parameters;
    }

}
