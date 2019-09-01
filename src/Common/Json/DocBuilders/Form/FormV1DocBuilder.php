<?php 

namespace Fabiom\UglyDuckling\Common\Json\DocBuilders\Form;

use Fabiom\UglyDuckling\Common\Json\DocBuilders\BasicDocBuilder;
use Fabiom\UglyDuckling\Common\Utils\StringUtils;

/**
 * Make all checks for form entity version 1
 */
class FormV1DocBuilder extends BasicDocBuilder {

    public function getDocText() {
        return $this->resource->name.'<br />';
    }

}
