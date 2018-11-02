<?php

/**
 * Created by Fabio Mattei
 * Date: 02/11/2018
 * Time: 04:56
 */

namespace Firststep\Common\Builders;

use Firststep\Common\Blocks\CardBlock;

class PanelBuilder {

    private $jsonloader;
    private $parameters;
    private $router;
    private $dbconnection;
    private $tableBuilder;
    private $chartjsBuilder;
    private $infoBuilder;

    /**
     * PanelBuilder constructor.
     * @param $tableBuilder
     */
    public function __construct() {
        $this->tableBuilder = new TableBuilder;
        $this->chartjsBuilder = new ChartjsBuilder;
        $this->infoBuilder = new InfoBuilder;
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

    function getPanel($panel) {
        $panelBlock = new CardBlock;
        $panelBlock->setTitle($panel->title ?? '');
        $panelBlock->setWidth($panel->width ?? '3');

        $resource = $this->jsonloader->loadResource( $panel->resource );

        if ($resource->metadata->type == 'table') {
            $this->tableBuilder->setRouter( $this->router );
            $this->tableBuilder->setResource( $resource );
            $this->tableBuilder->setParameters( $this->parameters );
            $this->tableBuilder->setDbconnection( $this->dbconnection );
            $panelBlock->setBlock($this->tableBuilder->createTable());
        }

        if ($resource->metadata->type == 'chartjs') {
            $this->chartjsBuilder->setRouter( $this->router );
            $this->chartjsBuilder->setResource( $resource );
            $this->chartjsBuilder->setParameters( $this->parameters );
            $this->chartjsBuilder->setDbconnection( $this->dbconnection );
            $panelBlock->setBlock($this->chartjsBuilder->createChart());
        }

        if ($resource->metadata->type == 'info') {
            $this->infoBuilder->setRouter( $this->router );
            $this->infoBuilder->setResource( $resource );
            $this->infoBuilder->setParameters( $this->parameters );
            $this->infoBuilder->setDbconnection( $this->dbconnection );
            $panelBlock->setBlock($this->infoBuilder->createInfo());
        }

        return $panelBlock;
    }

}
