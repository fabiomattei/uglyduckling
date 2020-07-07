<?php

namespace Fabiom\UglyDuckling\Common\Status;

class ControlCenter {

    private /* ApplicationBuilder */ $applicationBuilder;
    private /* PageStatus */ $pageStatus;

    public function __construct() {
        $this->applicationBuilder = new ApplicationBuilder;
        $this->pageStatus = new PageStatus;
    }



}
