<?php 

namespace Fabiom\UglyDuckling\Common\Json\DocBuilders\Group;

use Fabiom\UglyDuckling\Common\Json\DocBuilders\BasicDocBuilder;

/**
 * 
 */
class GroupV1DocBuilder extends BasicDocBuilder {

    public function getDocText() {
        return $this->resource->name.'<br />';
    }

}
