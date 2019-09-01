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

        $out .= $this->resource->get->query->sql . '<br />';

        foreach ($this->resource->get->form->fields as $field) {
            $out .= $field->headline . ' ' .  $field->sqlfield . '<br />';
        }

        return $out . '<br />';
    }

}
