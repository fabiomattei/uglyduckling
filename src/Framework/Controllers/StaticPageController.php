<?php

namespace Fabiom\UglyDuckling\Framework\Controllers;

use Fabiom\UglyDuckling\Framework\DataBase\DBConnection;
use Fabiom\UglyDuckling\Framework\Loggers\Logger;
use Fabiom\UglyDuckling\Framework\Mailer\BaseMailer;
use Fabiom\UglyDuckling\Framework\SecurityCheckers\SecurityChecker;
use Fabiom\UglyDuckling\Framework\Utils\ServerWrapper;
use Fabiom\UglyDuckling\Framework\Utils\SessionWrapper;

class StaticPageController {

    public string $templateFile;
    public string $staticPageFile;

    public function __construct( $templateFile, $staticPageFile ) {
        $this->templateFile = $templateFile;
        $this->staticPageFile = $staticPageFile;
    }

    public function showPage() {
        $this->loadTemplate();
    }

    function loadTemplate() {
        ob_start();
        require_once 'src/Templates/' . $this->staticPageFile . '.php';
        return ob_end_flush();
    }

    public function makeAllPresets(DBConnection $dbconnection, Logger $logger, SecurityChecker $securityChecker, BaseMailer $mailer) {
        // nothing to do
    }

}
