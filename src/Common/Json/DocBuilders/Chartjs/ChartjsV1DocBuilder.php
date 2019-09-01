<?php 

namespace Fabiom\UglyDuckling\Common\Json\DocBuilders\Chartjs;

use Fabiom\UglyDuckling\Common\Json\DocBuilders\BasicDocBuilder;
use Fabiom\UglyDuckling\Common\Utils\StringUtils;

/**
 * 
 */
class ChartjsV1DocBuilder extends BasicDocBuilder {

    public function getDocText() {
        $out = '\subsubsection{' . $this->resource->get->graphmeta->title . '}<br />';

        $out .= $this->resource->get->query->sql . '<br />';

        $out .= $this->resource->get->chart->options->scales->xAxes[0]->scaleLabel->labelString . '<br />';
        $out .= $this->resource->get->chart->options->scales->yAxes[0]->scaleLabel->labelString . '<br />';

        return $out . '<br />';
    }

}
