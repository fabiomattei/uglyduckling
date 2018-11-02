<?php

/**
 * Created by Fabio Mattei
 * Date: 01/11/18
 * Time: 10.15
 */

namespace Firststep\Common\Builders;

use Firststep\Common\Blocks\BaseChart;
use Firststep\Common\Database\QueryExecuter;

class ChartjsBuilder {

    private $queryExecuter;
    private $queryBuilder;
    private $resource;
    private $router;
    private $dbconnection;
    private $parameters;

    /**
     * ChartjsBuilder constructor.
     */
    public function __construct() {
        $this->queryExecuter = new QueryExecuter;
        $this->queryBuilder = new QueryBuilder;
    }


    public function setRouter( $router ) {
        $this->router = $router;
    }

    /**
     * @param mixed $parameters
     */
    public function setParameters($parameters) {
        $this->parameters = $parameters;
    }

    /**
     * @param mixed $resource
     */
    public function setResource($resource) {
        $this->resource = $resource;
    }

    /**
     * @param mixed $dbconnection
     */
    public function setDbconnection($dbconnection) {
        $this->dbconnection = $dbconnection;
    }

    public function createChart() {
        $this->queryExecuter->setDBH( $this->dbconnection->getDBH() );
        $this->queryExecuter->setQueryBuilder( $this->queryBuilder );
        $this->queryExecuter->setQueryStructure( $this->resource->get->query );
        if (isset( $this->parameters ) ) $this->queryExecuter->setParameters( $this->parameters );
        $entities = $this->queryExecuter->executeQuery();

        $chartBlock = new BaseChart;
        $chartBlock->setStructure($this->resource->get->chart);
        $chartBlock->setChartDataGlue($this->resource->get->chartdataglue);
        $chartBlock->setData($entities);
        return $chartBlock;
    }

}