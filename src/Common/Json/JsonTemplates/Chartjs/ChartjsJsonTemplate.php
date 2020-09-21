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
        $htmlTemplateLoader = $this->applicationBuilder->getHtmlTemplateLoader();
        $queryExecutor = $this->pageStatus->getQueryExecutor();

        $queryExecutor->setResourceName( $this->resource->name ?? 'undefined ');
        $queryExecutor->setQueryStructure( $this->resource->get->query );
        $entities = $queryExecutor->executeSql();

        $chartBlock = new BaseHTMLChart;
        $chartBlock->setHtmlTemplateLoader( $htmlTemplateLoader );
        $chartBlock->setHtmlBlockId($this->resource->name);
        $chartBlock->setStructure($this->resource->get->chart);
        $chartBlock->setChartDataGlue($this->resource->get->chartdataglue);
        $chartBlock->setData($entities);
        return $chartBlock;
    }

}