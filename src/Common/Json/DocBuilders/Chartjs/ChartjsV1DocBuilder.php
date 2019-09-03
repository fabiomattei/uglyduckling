<?php 

namespace Fabiom\UglyDuckling\Common\Json\DocBuilders\Chartjs;

use Fabiom\UglyDuckling\Common\Json\DocBuilders\BasicDocBuilder;
use Fabiom\UglyDuckling\Common\Utils\StringUtils;

/**
 * 
 */
class ChartjsV1DocBuilder extends BasicDocBuilder {

    public function getDocText() {
        $out = '\subsubsection{' . $this->resource->get->graphmeta->title . '}<br /><br />';

        $out .= '\begin{minted}{sql}' . '<br />';
        $out .= wordwrap($this->resource->get->query->sql, 80, '<br />') . '<br />';
        $out .= '\end{minted}' . '<br />';

        $out .= '\begin{table}[htbp]' . '<br />';
        $out .= '\begin{tabular}{|l|l|l|}' . '<br />';
        $out .= '\hline' . '<br />';

        $out .= 'X Axis: & '.$this->resource->get->chart->options->scales->xAxes[0]->scaleLabel->labelString . ' & ' . str_replace('_', '\_', $this->resource->get->chartdataglue[1]->sqlfield) . ' \\\\' . '<br />';
        $out .= 'Y Axis: & '.$this->resource->get->chart->options->scales->yAxes[0]->scaleLabel->labelString . ' & ' . str_replace('_', '\_', $this->resource->get->chartdataglue[0]->sqlfield) . ' \\\\' . '<br />';

        $out .= '\hline' . '<br />';
        $out .= '\end{tabular}' . '<br />';
        $out .= '\end{table}' . '<br />';


        return $out . '<br />';
    }

}
