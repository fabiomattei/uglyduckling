<?php

namespace Firststep\Common\Json\Metrics\Table;

use Firststep\Common\Json\Metrics\BaseResourceMetric;

/**
 * 
 */
class TableV1Metric extends BaseResourceMetric {

    public function getRET(): int {
        return $this->resource->ifpug->RET ?? 1;
    }

    public function getDET(): int {
        return $this->resource->ifpug->DET ?? count($this->resource->get->table->fields) ?? 0 ;
    }

}
