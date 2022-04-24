<?php

/**
 * Created Fabio Mattei
 * Date: 2019-05-23
 * Time: 21:34
 */

namespace Fabiom\UglyDuckling\Common\Json\Parameters;

use \Fabiom\UglyDuckling\Common\Json\Parameters\Dashboard\DashboardParameterGetter;
use Fabiom\UglyDuckling\Common\Json\Parameters\Dashboard\HTMLStaticBlockParametersGetter;
use Fabiom\UglyDuckling\Common\Json\Parameters\Dashboard\JsonResourceParametersGetter;
use Fabiom\UglyDuckling\Common\Json\Parameters\Dashboard\ParameterGetter;
use Fabiom\UglyDuckling\Common\Status\ApplicationBuilder;

class BasicParameterGetter {

    protected $resource;

    /**
     * @deprecated
     * @param $resource
     */
    function __construct( $resource ) {
        $this->resource = $resource;
    }

    /**
     * @deprecated
     * Check if an array of parameters is defined for a json resource in a get request
     * and eventually return an array containing all of them
     *
     * @return array
     */
    function getGetParameters(): array {
        if ( isset( $this->resource->get->request->parameters ) AND is_array( $this->resource->get->request->parameters ) ) {
            return $this->resource->get->request->parameters;
        } else {
            return array();
        }
    }

    /**
     * @deprecated
     * Check if an array of parameters is defined for a json resource in a post request
     * and eventually return an array containing all of them
     *
     * @return array
     */
    function getPostParameters(): array {
        if ( isset( $this->resource->post->request->postparameters ) AND is_array( $this->resource->post->request->postparameters ) ) {
            return $this->resource->post->request->postparameters;
        } else {
            return array();
        }
    }

    /**
     * @deprecated
     * Factory that defines the right parameter loader for a given resource
     *
     * @param $resource
     * @param $jsonloader
     * @return BasicParameterGetter
     */
    public static function basicParameterCheckerFactory( $resource, $jsonloader ): BasicParameterGetter {
        if ( $resource->metadata->type === "dashboard" ) return new DashboardParameterGetter( $resource, $jsonloader );
        return new BasicParameterGetter( $resource, $jsonloader );
    }

    /**
     * Factory that defines the right parameter loader for a given resource
     *
     * @param $resource
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
