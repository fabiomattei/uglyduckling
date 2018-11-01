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

    function __construct() {
        $this->lables = '';
        $this->dataset = '';
        $this->structure = '';
    }

    function setStructure($structure) {
        $this->structure = $structure;
    }

    function setData($data) {
        foreach ( $data as $dt ) {
            //$lables[] = $dt->;
            //$dataset[] = $dt->;
        }
        //$this->lables = implode(',',$lables);
        //$this->dataset = implode(',',$dataset);
    }

    function addToHead(): string {
        return '<script src="assets/js/lib/chartjs/Chart.bundle.min.js"></script>\n
<script src="assets/js/lib/chartjs/Chart.min.js"></script>';
    }

    function show(): string {
        //print_r($this->structure->data->datasets);
        //$this->structure->data->datasets[0]->data = $this->dataset;
        return "<canvas id=\"myChart\" width=\"400\" height=\"400\"></canvas>
                <script>
                    var ctx = document.getElementById(\"myChart\").getContext('2d');
                    var myChart = new Chart(ctx, ".json_encode($this->structure).");
                </script>";
    }

}
