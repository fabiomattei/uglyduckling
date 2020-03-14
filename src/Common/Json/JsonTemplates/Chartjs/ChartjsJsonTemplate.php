<?php

/**
 * Created by Fabio Mattei
 * Date: 01/11/18
 * Time: 10.15
 */

namespace Fabiom\UglyDuckling\Common\Json\JsonTemplates\Chartjs;

use Fabiom\UglyDuckling\Common\Blocks\BaseHTMLChart;
use Fabiom\UglyDuckling\Common\Json\JsonTemplates\JsonTemplate;

class ChartjsJsonTemplate extends JsonTemplate {

    const blocktype = 'chartjs';

    public function createChart() {
        $this->queryExecuter->setDBH( $this->dbconnection->getDBH() );
        $this->queryExecuter->setQueryBuilder( $this->queryBuilder );
        $this->queryExecuter->setQueryStructure( $this->resource->get->query );
        $this->queryExecuter->setLogger( $this->logger );
        $this->queryExecuter->setSessionWrapper( $this->sessionWrapper );
        if (isset( $this->parameters ) ) $this->queryExecuter->setParameters( $this->parameters );
        $entities = $this->queryExecuter->executeQuery();

        $chartBlock = new BaseHTMLChart;
        $chartBlock->setHtmlTemplateLoader( $this->htmlTemplateLoader );
        $chartBlock->setStructure($this->resource->get->chart);
        $chartBlock->setChartDataGlue($this->resource->get->chartdataglue);
        $chartBlock->setData($entities);
        return $chartBlock;
    }

}