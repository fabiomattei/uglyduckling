<?php

namespace Fabiom\UglyDuckling\Common\Json\DocBuilders;

use Fabiom\UglyDuckling\Common\Json\DocBuilders\Breadcrumbs\BreadcrumbsV1DocBuilder;
use Fabiom\UglyDuckling\Common\Json\DocBuilders\Chartjs\ChartjsV1DocBuilder;
use Fabiom\UglyDuckling\Common\Json\DocBuilders\Dashboard\DashboardV1DocBuilder;
use Fabiom\UglyDuckling\Common\Json\DocBuilders\Form\FormV1DocBuilder;
use Fabiom\UglyDuckling\Common\Json\DocBuilders\Info\InfoV1DocBuilder;
use Fabiom\UglyDuckling\Common\Json\DocBuilders\Group\GroupV1DocBuilder;
use Fabiom\UglyDuckling\Common\Json\DocBuilders\TabbedPage\TabbedPageV1DocBuilder;
use Fabiom\UglyDuckling\Common\Json\DocBuilders\Table\TableV1DocBuilder;
use Fabiom\UglyDuckling\Common\Json\DocBuilders\Title\TitleV1DocBuilder;
use Fabiom\UglyDuckling\Common\Json\DocBuilders\Transaction\TransactionV1DocBuilder;

/**
 * Basic class for json files checkers
 * Contains the factory to generate the right json checker based on the json resource we need to check
 * Contains the basic structure
 */
class BasicDocBuilder {

    protected $resource;
    protected $jsonLoader;
    protected $errors = array();

    function __construct( $resource, $jsonLoader ) {
        $this->resource = $resource;
        $this->jsonLoader = $jsonLoader;
    }

    /**
     * @param mixed $resource
     */
    public function setResource( $resource ) {
        $this->resource = $resource;
    }

    public function getDocText() {
        return $this->resource->name.'<br />';
    }

    public static function basicJsonDocBuilderFactory( $resource, $jsonLoader ): BasicDocBuilder {
        if ( $resource->metadata->type === "chartjs" OR $resource->metadata->type === "searchchart" )     return new ChartjsV1DocBuilder( $resource, $jsonLoader );
        if ( $resource->metadata->type === "dashboard" )   return new DashboardV1DocBuilder( $resource, $jsonLoader );
        if ( $resource->metadata->type === "form" )        return new FormV1DocBuilder( $resource, $jsonLoader );
        if ( $resource->metadata->type === "group" )       return new GroupV1DocBuilder( $resource, $jsonLoader );
        if ( $resource->metadata->type === "info" )        return new InfoV1DocBuilder( $resource, $jsonLoader );
        if ( $resource->metadata->type === "table" OR $resource->metadata->type === "datatable" )       return new TableV1DocBuilder( $resource, $jsonLoader );
        if ( $resource->metadata->type === "tabbedpage" )  return new TabbedPageV1DocBuilder( $resource, $jsonLoader );
        if ( $resource->metadata->type === "transaction" ) return new TransactionV1DocBuilder( $resource, $jsonLoader );
        if ( $resource->metadata->type === "breadcrumbs" ) return new BreadcrumbsV1DocBuilder( $resource, $jsonLoader );
        if ( $resource->metadata->type === "titlebar" ) return new TitleV1DocBuilder( $resource, $jsonLoader );
        return new BasicDocBuilder( $resource, $jsonLoader );
    }

}
