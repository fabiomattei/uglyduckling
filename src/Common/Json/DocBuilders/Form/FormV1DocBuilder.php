<?php 

namespace Fabiom\UglyDuckling\Common\Json\DocBuilders\Form;

use Fabiom\UglyDuckling\Common\Json\DocBuilders\BasicDocBuilder;
use Fabiom\UglyDuckling\Common\Utils\StringUtils;

/**
 * Make all checks for form entity version 1
 */
class FormV1DocBuilder extends BasicDocBuilder {

    public function getDocText() {
        $out = '\subsubsection{' . $this->resource->get->form->title . '}<br />';

        $out .= '\begin{minted}{sql}' . '<br />';
        $out .= wordwrap(str_replace('_', '\_', $this->resource->get->query->sql), 80, '<br />') . '<br />';
        $out .= '\end{minted}' . '<br />';

        $out .= '\begin{table}[htbp]' . '<br />';
        $out .= '\centering' . '<br />';
        $out .= '\begin{tabular}{|l|l|}' . '<br />';
        $out .= '\hline' . '<br />';
        $out .= 'Field name & SQL field \\\\' . '<br />';
        $out .= '\hline' . '<br />';
        foreach ($this->resource->get->form->fields as $field) {
            $out .= $field->headline . ' & ' .  $field->sqlfield . ' \\\\' . '<br />';
        }
        $out .= '\hline' . '<br />';
        $out .= '\end{tabular}' . '<br />';
        $out .= '\end{table}' . '<br />';

        return $out . '<br />';
    }

}
