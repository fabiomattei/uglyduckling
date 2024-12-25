<?php

/**
 * Created Fabio Mattei
 * Date: 2019-05-23
 * Time: 21:34
 */

namespace Fabiom\UglyDuckling\Framework\Json\Parameters;

use \Fabiom\UglyDuckling\Framework\Json\Parameters\Dashboard\DashboardParameterGetter;
use Fabiom\UglyDuckling\Framework\Json\Parameters\Dashboard\HTMLStaticBlockParametersGetter;
use Fabiom\UglyDuckling\Framework\Json\Parameters\Dashboard\JsonResourceParametersGetter;
use Fabiom\UglyDuckling\Framework\Json\Parameters\Dashboard\ParameterGetter;

class BasicParameterGetter {

    /**
     * Factory that defines the right parameter loader for a given resource
     *
     * @param $resource (it may be a Json Resource or a HTMLStaticBlock resource)
     * @param ApplicationBuilder $applicationBuilder
     * @return ParameterGetter
     */
    public static function parameterGetterFactory( $resource, $resourceIndex ): ParameterGetter {
        if ( is_a($resource, 'Fabiom\UglyDuckling\Common\Blocks\BaseHTMLStaticBlock') ) {
            return new HTMLStaticBlockParametersGetter($resource);
        }
        if ( $resource->metadata->type === "dashboard" ) {
            return new DashboardParameterGetter( $resource, $resourceIndex );
        }
        return new JsonResourceParametersGetter( $resource );
    }

}
