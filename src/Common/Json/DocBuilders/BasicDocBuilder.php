<?php

namespace Fabiom\UglyDuckling\Common\Json\DocBuilders;

use Fabiom\UglyDuckling\Common\Json\Checkers\Chartjs\ChartjsV1JsonChecker;
use Fabiom\UglyDuckling\Common\Json\Checkers\Dashboard\DashboardV1JsonChecker;
use Fabiom\UglyDuckling\Common\Json\Checkers\Form\FormV1JsonChecker;
use Fabiom\UglyDuckling\Common\Json\Checkers\Info\InfoV1JsonChecker;
use Fabiom\UglyDuckling\Common\Json\Checkers\Group\GroupV1JsonChecker;
use Fabiom\UglyDuckling\Common\Json\Checkers\TabbedPage\TabbedPageV1JsonChecker;
use Fabiom\UglyDuckling\Common\Json\Checkers\Table\TableV1JsonChecker;
use Fabiom\UglyDuckling\Common\Json\Checkers\Transaction\TransactionV1JsonChecker;

/**
 * Basic class for json files checkers
 * Contains the factory to generate the right json checker based on the json resource we need to check
 * Contains the basic structure
 */
class BasicDocBuilder {

    protected $resource;
    protected $errors = array();

    function __construct( $resource ) {
        $this->resource = $resource;
    }

    /**
     * @param mixed $resource
     */
    public function setResource( $resource ) {
        $this->resource = $resource;
    }

    public function getDocText() {
        $this->resource->name;
    }

    public static function basicJsonDocBuilderFactory( $resource ): BasicDocBuilder {
        /*
        if ( $resource->metadata->type === "chartjs" )     return new ChartjsV1JsonChecker( $resource );
        if ( $resource->metadata->type === "dashboard" )   return new DashboardV1JsonChecker( $resource );
        if ( $resource->metadata->type === "form" )        return new FormV1JsonChecker( $resource );
        if ( $resource->metadata->type === "group" )       return new GroupV1JsonChecker( $resource );
        if ( $resource->metadata->type === "info" )        return new InfoV1JsonChecker( $resource );
        if ( $resource->metadata->type === "table" OR $resource->metadata->type === "datatable" )       return new TableV1JsonChecker( $resource );
        if ( $resource->metadata->type === "tabbedpage" )  return new TabbedPageV1JsonChecker( $resource );
        if ( $resource->metadata->type === "transaction" ) return new TransactionV1JsonChecker( $resource );
        */
        return new BasicDocBuilder( $resource );
    }

}
