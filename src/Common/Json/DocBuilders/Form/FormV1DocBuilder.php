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

        $out .= wordwrap($this->resource->get->query->sql, 40, '<br />') . '<br />';

        foreach ($this->resource->get->form->fields as $field) {
            $out .= $field->headline . ' ' .  $field->sqlfield . '<br />';
        }

        return $out . '<br />';
    }

}
