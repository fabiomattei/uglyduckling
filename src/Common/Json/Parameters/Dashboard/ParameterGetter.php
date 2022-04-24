<?php

namespace Fabiom\UglyDuckling\Common\Json\Parameters\Dashboard;

interface ParameterGetter {

    public function getValidationRoules();

    public function getFiltersRoules();

    public function getPostValidationRoules();

    public function getPostFiltersRoules();

}
