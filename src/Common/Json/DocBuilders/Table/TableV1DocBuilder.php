<?php

namespace Fabiom\UglyDuckling\Common\Json\DocBuilders\Table;

use Fabiom\UglyDuckling\Common\Json\DocBuilders\BasicDocBuilder;
use Fabiom\UglyDuckling\Common\Utils\StringUtils;

/**
 *
 */
class TableV1DocBuilder extends BasicDocBuilder {

    public function getDocText() {
        $out = '\subsubsection{' . $this->resource->get->table->title . '}<br /><br />';
        $out .= '% ' . $this->resource->name . ' <br />';
        $out .= '\begin{minted}{sql}' . '<br />';
        $out .= wordwrap($this->resource->get->query->sql, 70, '<br />') . '<br />';
        $out .= '\end{minted}' . '<br />';

        $out .= '\begin{table}[htbp]' . '<br />';
        $out .= '\begin{tabular}{|l|l|}' . '<br />';
        $out .= '\hline' . '<br />';
        $out .= 'Field name & SQL field \\\\' . '<br />';
        $out .= '\hline' . '<br />';
        foreach ($this->resource->get->table->fields as $field) {
            $out .= $field->headline . ' & ' . str_replace('_', '\_', $field->sqlfield) . ' \\\\' . '<br />';
        }
        $out .= '\hline' . '<br />';
        $out .= '\end{tabular}' . '<br />';
        $out .= '\end{table}' . '<br />';

        return $out . '<br />';
    }

}