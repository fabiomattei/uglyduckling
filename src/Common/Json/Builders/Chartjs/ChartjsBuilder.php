<?php

/**
 * Created by Fabio Mattei
 * Date: 01/11/18
 * Time: 10.15
 */

namespace Firststep\Common\Json\Builders\Chartjs;

use Firststep\Common\Blocks\BaseHTMLChart;
use Firststep\Common\Json\Builders\BaseBuilder;

class ChartjsBuilder extends BaseBuilder {

    const blocktype = 'chartjs';

    public function createChart() {
        $this->queryExecuter->setDBH( $this->dbconnection->getDBH() );
        $this->queryExecuter->setQueryBuilder( $this->queryBuilder );
        $this->queryExecuter->setQueryStructure( $this->resource->get->query );
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