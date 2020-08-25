<?php

namespace Fabiom\UglyDuckling\Common\Json\JsonTemplates\Chartjs;

use Fabiom\UglyDuckling\Common\Blocks\BaseHTMLChart;
use Fabiom\UglyDuckling\Common\Json\JsonTemplates\JsonTemplate;

/**
 * Created by Fabio Mattei
 * Date: 01/11/18
 * Time: 10.15
 */
class ChartjsJsonTemplate extends JsonTemplate {

    const blocktype = 'chartjs';

    public function createChart() {
        $queryExecuter = $this->jsonTemplateFactoriesContainer->getQueryExecuter();
        $queryBuilder = $this->jsonTemplateFactoriesContainer->getQueryBuilder();
        $parameters = $this->jsonTemplateFactoriesContainer->getParameters();
        $dbconnection = $this->jsonTemplateFactoriesContainer->getDbconnection();
        $logger = $this->jsonTemplateFactoriesContainer->getLogger();
        $htmlTemplateLoader = $this->jsonTemplateFactoriesContainer->getHtmlTemplateLoader();
        $sessionWrapper = $this->jsonTemplateFactoriesContainer->getSessionWrapper();
        $linkBuilder = $this->jsonTemplateFactoriesContainer->getLinkBuilder();
        $jsonloader = $this->jsonTemplateFactoriesContainer->getJsonloader();
        $routerContainer = $this->jsonTemplateFactoriesContainer->getRouterContainer();

        $queryExecuter->setDBH( $dbconnection->getDBH() );
		$queryExecuter->setResourceName( $this->resource->name ?? 'undefined ');
        $queryExecuter->setQueryBuilder( $queryBuilder );
        $queryExecuter->setQueryStructure( $this->resource->get->query );
        $queryExecuter->setLogger( $logger );
        $queryExecuter->setSessionWrapper( $sessionWrapper );
        if (isset( $parameters ) ) $queryExecuter->setParameters( $parameters );
        $entities = $queryExecuter->executeSql();

        $chartBlock = new BaseHTMLChart;
        $chartBlock->setHtmlTemplateLoader( $htmlTemplateLoader );
        $chartBlock->setHtmlBlockId($this->resource->name);
        $chartBlock->setStructure($this->resource->get->chart);
        $chartBlock->setChartDataGlue($this->resource->get->chartdataglue);
        $chartBlock->setData($entities);
        return $chartBlock;
    }

}