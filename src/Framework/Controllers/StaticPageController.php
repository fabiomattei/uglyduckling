<?php

namespace Fabiom\UglyDuckling\Framework\Controllers;

class StaticPageController {

    public string $templateFile;
    public string $staticPageFile;

    public function __construct( $templateFile, $staticPageFile ) {
        $this->templateFile = $templateFile;
        $this->staticPageFile = $staticPageFile;
    }

    public function showPage() {
        $this->viewFile = $this->staticPageFile;
    }

    function loadTemplate() {
        ob_start();
        require_once 'src/Templates/' . $this->templateFile . '.php';
        return ob_end_flush();
    }

}
