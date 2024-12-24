<?php

/**
 * Created Fabio Mattei
 * Date: 2019-05-23
 * Time: 21:34
 */

namespace Fabiom\UglyDuckling\Framework\Json\Parameters;

use \Fabiom\UglyDuckling\Common\Json\Parameters\Dashboard\DashboardParameterGetter;
use Fabiom\UglyDuckling\Common\Json\Parameters\Dashboard\HTMLStaticBlockParametersGetter;
use Fabiom\UglyDuckling\Common\Json\Parameters\Dashboard\JsonResourceParametersGetter;
use Fabiom\UglyDuckling\Common\Json\Parameters\Dashboard\ParameterGetter;
use Fabiom\UglyDuckling\Common\Status\ApplicationBuilder;

class BasicParameterGetter {

    /**
     * Factory that defines the right parameter loader for a given resource
     *
     * @param $resource (it may be a Json Resource or a HTMLStaticBlock resource)
     * @param ApplicationBuilder $applicationBuilder
     * @return ParameterGetter
     */
    public static function parameterGetterFactory( $resource, ApplicationBuilder $applicationBuilder ): ParameterGetter {
        if ( is_a($resource, 'Fabiom\UglyDuckling\Common\Blocks\BaseHTMLStaticBlock') ) {
            return new HTMLStaticBlockParametersGetter($resource);
        }
        if ( $resource->metadata->type === "dashboard" ) {
            return new DashboardParameterGetter( $resource, $applicationBuilder );
        }
        return new JsonResourceParametersGetter( $resource );
    }

}
