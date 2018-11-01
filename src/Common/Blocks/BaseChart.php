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

    function __construct() {
        $this->lables = '';
        $this->dataset = '';
        $this->structure = '';
        $this->chartdataglue = array();
        $this->glue = array();
    }

    function setStructure($structure) {
        $this->structure = $structure;
    }

    function setChartDataGlue($chartDataGlue) {
        $this->chartdataglue = $chartDataGlue;
    }

    function setData($data) {
        $toadd = array();
        foreach ( $data as $dt ) {
            foreach ($this->chartdataglue as $dg) {
                if(!isset($toadd[$dg->placeholder])) $toadd[$dg->placeholder] = array();
                $toadd[$dg->placeholder][] = $dt->{$dg->sqlfield};
            }
        }
        $this->glue = $toadd;
    }

    function addToHead(): string {
        return '<script src="assets/js/lib/chartjs/Chart.bundle.min.js"></script>\n
<script src="assets/js/lib/chartjs/Chart.min.js"></script>';
    }

    function show(): string {
        $this->structure->data->labels = $this->glue['#labels'];
        $this->structure->data->datasets[0]->data = $this->glue['#amounts'];
        return "<canvas id=\"myChart\" width=\"400\" height=\"400\"></canvas>
                <script>
                    var ctx = document.getElementById(\"myChart\").getContext('2d');
                    var myChart = new Chart(ctx, ".json_encode( $this->structure ).");
                </script>";
    }

}
