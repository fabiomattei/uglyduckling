<?php

/**
 * Created by Fabio Mattei
 * Date: 02/11/2018
 * Time: 04:56
 */

namespace Firststep\Common\Json\Builders;

use Firststep\Common\Blocks\CardBlock;
use Firststep\Common\Router\Router;

class PanelBuilder {

    private $jsonloader;
    private $parameters;
    private $router;
    private $dbconnection;
    private $action;
    private $tableBuilder;
    private $chartjsBuilder;
    private $infoBuilder;
    private $formBuilder;
    private $htmlTemplateLoader;

    /**
     * PanelBuilder constructor.
     * @param $tableBuilder
     */
    public function __construct() {
        $this->tableBuilder = new TableBuilder;
        $this->chartjsBuilder = new ChartjsBuilder;
        $this->infoBuilder = new InfoBuilder;
        $this->formBuilder = new FormBuilder;
        $this->action = '';
    }


    /**
     * @param mixed $jsonloader
     */
    public function setJsonloader($jsonloader) {
        $this->jsonloader = $jsonloader;
    }

    /**
     * @param mixed $parameters
     */
    public function setParameters($parameters) {
        $this->parameters = $parameters;
    }

    /**
     * @param mixed $router
     */
    public function setRouter($router) {
        $this->router = $router;
    }

    /**
     * @param mixed $dbconnection
     */
    public function setDbconnection($dbconnection) {
        $this->dbconnection = $dbconnection;
    }

    /**
     * @param mixed $action
     */
    public function setAction(string $action) {
        $this->action = $action;
    }

    function getPanel($panel) {
        $panelBlock = new CardBlock;
        $panelBlock->setTitle($panel->title ?? '');
        $panelBlock->setWidth($panel->width ?? '3');
        $panelBlock->setHtmlTemplateLoader( $this->htmlTemplateLoader );

        $resource = $this->jsonloader->loadResource( $panel->resource );

        if ($resource->metadata->type == 'table') {
            $this->tableBuilder->setHtmlTemplateLoader( $this->htmlTemplateLoader );
            $this->tableBuilder->setRouter( $this->router );
            $this->tableBuilder->setResource( $resource );
            $this->tableBuilder->setParameters( $this->parameters );
            $this->tableBuilder->setDbconnection( $this->dbconnection );
            $panelBlock->setBlock($this->tableBuilder->createTable());
        }

        if ($resource->metadata->type == 'chartjs') {
            $this->chartjsBuilder->setHtmlTemplateLoader( $this->htmlTemplateLoader );
            $this->chartjsBuilder->setRouter( $this->router );
            $this->chartjsBuilder->setResource( $resource );
            $this->chartjsBuilder->setParameters( $this->parameters );
            $this->chartjsBuilder->setDbconnection( $this->dbconnection );
            $panelBlock->setBlock($this->chartjsBuilder->createChart());
        }

        if ($resource->metadata->type == 'info') {
            $this->infoBuilder->setHtmlTemplateLoader( $this->htmlTemplateLoader );
            $this->infoBuilder->setRouter( $this->router );
            $this->infoBuilder->setResource( $resource );
            $this->infoBuilder->setParameters( $this->parameters );
            $this->infoBuilder->setDbconnection( $this->dbconnection );
            $panelBlock->setBlock($this->infoBuilder->createInfo());
        }

        if ($resource->metadata->type == 'form') {
            $this->formBuilder->setHtmlTemplateLoader( $this->htmlTemplateLoader );
            $this->formBuilder->setRouter( $this->router );
            $this->formBuilder->setResource( $resource );
            $this->formBuilder->setParameters( $this->parameters );
            $this->formBuilder->setDbconnection( $this->dbconnection );
            $this->formBuilder->setAction( $this->action .'&postres='.$resource->name );
            $panelBlock->setBlock($this->formBuilder->createForm());
        }

        if ($resource->metadata->type == 'search') {
            $this->formBuilder->setHtmlTemplateLoader( $this->htmlTemplateLoader );
            $this->formBuilder->setRouter( $this->router );
            $this->formBuilder->setResource( $resource );
            $this->formBuilder->setParameters( $this->parameters );
            $this->formBuilder->setDbconnection( $this->dbconnection );
            $this->formBuilder->setAction( $this->router->make_url( Router::ROUTE_OFFICE_ENTITY_SEARCH, 'res='.$resource->name ) );
            $panelBlock->setBlock($this->formBuilder->createForm());
        }

        if ($resource->metadata->type == 'export') {
            $this->formBuilder->setHtmlTemplateLoader( $this->htmlTemplateLoader );
            $this->formBuilder->setRouter( $this->router );
            $this->formBuilder->setResource( $resource );
            $this->formBuilder->setParameters( $this->parameters );
            $this->formBuilder->setDbconnection( $this->dbconnection );
            $this->formBuilder->setAction( $this->router->make_url( Router::ROUTE_OFFICE_ENTITY_EXPORT, 'res='.$resource->name ) );
            $panelBlock->setBlock($this->formBuilder->createForm());
        }

        return $panelBlock;
    }

    public function setHtmlTemplateLoader($htmlTemplateLoader) {
        $this->htmlTemplateLoader = $htmlTemplateLoader;
    }

}