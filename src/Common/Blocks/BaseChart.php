<?php

/**
 * Created by fabio
 * Date: 01/11/18
 * Time: 9.43
 */

namespace Firststep\Common\Blocks;

use Firststep\Common\Blocks\BaseBlock;

class BaseChart extends BaseBlock {

    private $dataset;
    private $structure;

    function __construct() {
        $this->dataset = '';
        $this->structure = '';
    }

    function setStructure($structure) {
        $this->structure = $structure;
    }

    function setData($data) {
        $this->dataset = implode(',',$data);
    }

    function addToHead(): string {
        return '<script src="assets/lib/chartjs/Chart.bundle.min.js"></script>\n
<script src="assets/lib/chartjs/Chart.min.js"></script>';
    }

    function show(): string {
        $this->structure->data->datasets->data = $this->dataset;
        return "<canvas id=\"myChart\" width=\"400\" height=\"400\"></canvas>
                <script>
                    var ctx = document.getElementById(\"myChart\").getContext('2d');
                    var myChart = new Chart(ctx, ".$this->structure.");
                </script>";
    }

}
