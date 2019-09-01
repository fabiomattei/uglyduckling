<?php

namespace Fabiom\UglyDuckling\Common\Json\DocBuilders\Table;

use Fabiom\UglyDuckling\Common\Json\DocBuilders\BasicDocBuilder;
use Fabiom\UglyDuckling\Common\Utils\StringUtils;

/**
 *
 */
class TableV1DocBuilder extends BasicDocBuilder {

    public function getDocText() {
        $out = '\subsubsection{' . $this->resource->get->table->title . '}<br />';

        $out .= $this->resource->get->query->sql . '<br />';

        foreach ($this->resource->get->table->fields as $field) {
           $out .= $field->headline . ' ' .  $field->sqlfield . '<br />';
        }

        return $out . '<br />';
    }

}
