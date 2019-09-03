<?php

namespace Fabiom\UglyDuckling\Common\Json\DocBuilders\HeatMap;

use Fabiom\UglyDuckling\Common\Json\DocBuilders\BasicDocBuilder;
use Fabiom\UglyDuckling\Common\Utils\StringUtils;

/**
 *
 */
class php extends BasicDocBuilder {

    public function getDocText() {
        $out = '\subsubsection{' . $this->resource->get->heatmap->title . '}<br /><br />';

        $out .= '\begin{minted}{sql}' . '<br />';
        $out .= wordwrap($this->resource->get->query->sql, 80, '<br />') . '<br />';
        $out .= '\end{minted}' . '<br />';

        $out .= '\begin{table}[htbp]' . '<br />';
        $out .= '\begin{tabular}{|l|l|l|}' . '<br />';
        $out .= '\hline' . '<br />';

        $out .= 'X Axis: & '.$this->resource->get->heatmap->xaxis->label . ' & ' . str_replace('_', '\_', $this->resource->get->heatmap->xaxis->sqlfield) . ' \\\\' . '<br />';
        $out .= 'Y Axis: & '.$this->resource->get->heatmap->yaxis->label . ' & ' . str_replace('_', '\_', $this->resource->get->heatmap->yaxis->sqlfield) . ' \\\\' . '<br />';

        $out .= '\hline' . '<br />';
        $out .= '\end{tabular}' . '<br />';
        $out .= '\end{table}' . '<br />';


        return $out . '<br />';
    }

}
