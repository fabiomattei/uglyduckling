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
		
        // If there are dummy data they take precedence in order to fill the table
        if ( isset($this->resource->get->dummydata) ) {
            $entities = $this->resource->get->dummydata;
        } else {
            // If there is a query I look for data to fill the table,
            // if there is not query I do not
            if ( isset($this->resource->get->query) ) {
		        $queryExecutor = $this->pageStatus->getQueryExecutor();
		        $queryExecutor->setResourceName( $this->resource->name ?? 'undefined ');
		        $queryExecutor->setQueryStructure( $this->resource->get->query );
		        $entities = $queryExecutor->executeSql();
            }
        }
        
        $chartBlock = new BaseHTMLChart;
        $chartBlock->setHtmlTemplateLoader( $htmlTemplateLoader );
        $chartBlock->setHtmlBlockId($this->resource->name);
        $chartBlock->setStructure($this->resource->get->chart);
        $chartBlock->setChartDataGlue($this->resource->get->chartdataglue);
        $chartBlock->setData($entities);
        return $chartBlock;
    }

}