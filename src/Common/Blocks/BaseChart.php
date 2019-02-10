<?php

/**
 * Created by Fabio Mattei
 * Date: 01/11/18
 * Time: 9.43
 */

namespace Firststep\Common\Blocks;

use Firststep\Common\Blocks\BaseBlock;

class BaseChart extends BaseBlock {

    private $lables;
    private $dataset;
    private $structure;
    private $chartdataglue;
    private $glue;
    private $htmlTemplateLoader;

    function __construct() {
        $this->lables = '';
        $this->dataset = '';
        $this->structure = '';
        $this->chartdataglue = array();
        $this->glue = array();
    }

    public function setHtmlTemplateLoader($htmlTemplateLoader) {
        $this->htmlTemplateLoader = $htmlTemplateLoader;
    }

    function setStructure($structure) {
        $this->structure = $structure;
    }

    function setChartDataGlue($chartDataGlue) {
        $this->chartdataglue = $chartDataGlue;
    }

    function setData($data) {
        foreach ( $data as $dt ) {
            foreach ($this->chartdataglue as $dg) {
                if(!isset($this->glue[$dg->placeholder])) $this->glue[$dg->placeholder] = array();
                $this->glue[$dg->placeholder][] = $dt->{$dg->sqlfield};
            }
        }
    }

    function addToHead(): string {
        return $this->htmlTemplateLoader->loadTemplateAndReplace(
            array(),
            array(),
            'Chartjs/addtohead.html');
    }

    function show(): string {
        $this->structure->data->labels = $this->glue['#labels'];
        $this->structure->data->datasets[0]->data = $this->glue['#amounts'];
        return $this->htmlTemplateLoader->loadTemplateAndReplace(
            array( '${structure}' ),
            array( json_encode( $this->structure ) ),
            'Chartjs/body.html');
    }

}
