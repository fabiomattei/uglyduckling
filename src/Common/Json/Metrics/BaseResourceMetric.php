<?php

namespace Firststep\Common\Json\Metrics;

use Firststep\Common\Json\Metrics\Table\TableV1Metric;

/**
 * This class is a model for the IFPUG metric reource calculation
 */
class BaseResourceMetric {

	protected $resource;

	function __construct( $resource ) {
        $this->resource = $resource;
	}

	/**
     * @param mixed $resource
     */
    public function setResource( $resource ) {
        $this->resource = $resource;
    }

    public function getFunctionPoints(): int {
    	return 0;
    }

    public static function basicResourceMetricFactory( $resource ): BaseResourceMetric {
        if ( $resource->metadata->type === "chartjs" )     return new BaseResourceMetric( $resource );
        if ( $resource->metadata->type === "dashboard" )   return new BaseResourceMetric( $resource );
        if ( $resource->metadata->type === "form" )        return new BaseResourceMetric( $resource );
        if ( $resource->metadata->type === "group" )       return new BaseResourceMetric( $resource );
        if ( $resource->metadata->type === "info" )        return new BaseResourceMetric( $resource );
        if ( $resource->metadata->type === "table" OR $resource->metadata->type === "datatable" )       return new TableV1Metric( $resource );
        if ( $resource->metadata->type === "tabbedpage" )  return new BaseResourceMetric( $resource );
        if ( $resource->metadata->type === "transaction" ) return new BaseResourceMetric( $resource );
        return new BaseResourceMetric( $resource );
	}

}

