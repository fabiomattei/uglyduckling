<?php

namespace Fabiom\UglyDuckling\Framework\Controllers;

use Fabiom\UglyDuckling\Framework\DataBase\DBConnection;
use Fabiom\UglyDuckling\Framework\Loggers\Logger;
use Fabiom\UglyDuckling\Framework\Mailer\BaseMailer;
use Fabiom\UglyDuckling\Framework\SecurityCheckers\SecurityChecker;

class StaticPageController {

    public string $templateFile;
    public string $viewFile;
    public $controllerPointer;

    public function __construct( $templateFile, $staticPageFile ) {
        $this->templateFile = $templateFile;
        $this->viewFile = $staticPageFile;
        $this->controllerPointer = $this;
        $this->appTitle = APP_NAME;
    }

    public function showPage() {
        $this->loadTemplate();
    }

    function loadTemplate() {
        ob_start() && extract(get_object_vars($this->controllerPointer), EXTR_SKIP);
        require_once 'src/Templates/' . $this->templateFile . '.php';
        return ob_end_flush();
    }

    public function makeAllPresets(DBConnection $dbconnection, Logger $logger, SecurityChecker $securityChecker, BaseMailer $mailer) {
        // nothing to do
    }

}
