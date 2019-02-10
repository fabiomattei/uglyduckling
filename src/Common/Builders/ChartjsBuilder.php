<?php

/**
 * Created by Fabio Mattei
 * Date: 01/11/18
 * Time: 10.15
 */

namespace Firststep\Common\Builders;

use Firststep\Common\Blocks\BaseChart;
use Firststep\Common\Database\QueryExecuter;

class ChartjsBuilder extends BaseBuilder {

    public function createChart() {
        $this->queryExecuter->setDBH( $this->dbconnection->getDBH() );
        $this->queryExecuter->setQueryBuilder( $this->queryBuilder );
        $this->queryExecuter->setQueryStructure( $this->resource->get->query );
        if (isset( $this->parameters ) ) $this->queryExecuter->setParameters( $this->parameters );
        $entities = $this->queryExecuter->executeQuery();

        $chartBlock = new BaseChart;
        $chartBlock->setHtmlTemplateLoader( $this->htmlTemplateLoader );
        $chartBlock->setStructure($this->resource->get->chart);
        $chartBlock->setChartDataGlue($this->resource->get->chartdataglue);
        $chartBlock->setData($entities);
        return $chartBlock;
    }

}