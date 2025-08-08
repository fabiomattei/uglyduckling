<?php

namespace Fabiom\UglyDuckling\Framework\Blocks;

use Fabiom\UglyDuckling\Framework\Json\JsonLoader;
use Fabiom\UglyDuckling\Framework\Utils\HtmlTemplateLoader;
use Fabiom\UglyDuckling\Framework\Utils\PageStatus;
use Fabiom\UglyDuckling\Framework\SecurityCheckers\PublicSecurityChecker;
use Fabiom\UglyDuckling\Framework\Mailer\NullMailer;

/**
 * Class BaseHTMLUniform
 *
 * It is un unparemitrized block
 */
class BaseHTMLController extends BaseHTMLBlock {

    /**
     * Necessary in order to load the HTML surrounding code
     */
    private $resourceName;
    private $className;
    private $templateFile;

    private PageStatus $pageStatus;
    private $addToHeadFile;
    private $addToFootFile;
    private $addToHeadOnceFile;
    private $addToFootOnceFile;


    public function __construct() {
        parent::__construct();
        $this->resourceName = '';
        $this->className = '';
        $this->templateFile = '';
    }

    public function setResourceName( string $resourceName ) {
        $this->resourceName = $resourceName;
    }

    public function setClassName( string $className ) {
        $this->className = $className;
    }

    public function setTemplateFile( string $templateFile ) {
        $this->templateFile = $templateFile;
    }

    public function setPageStatus( PageStatus $pageStatus ) {
        $this->pageStatus = $pageStatus;
    }

    /**
     * it return the HTML code for the web page
     */
    function getHTML(): string {
        if (JsonLoader::isMobile()) {
            if (class_exists($this->className.'Mobile')) {
                $controller = new ($this->className.'Mobile');
            } elseif (class_exists($this->className)) {
                $controller = new $this->className;
            } else {
                $controller = null;
            }
        } else {
            if (class_exists($this->className)) {
                $controller = new $this->className;
            } else {
                $controller = null;
            }
        }

        if ($controller !== null) {
            //$controller->setGroupsIndex( $index_groups );
            $controller->setGroupsIndex( [] );
            $controller->setPageStatus($this->pageStatus);
            $controller->setTemplateFile($this->templateFile);
            //$controller->setControllerName($controllerName);
            $controller->setControllerName($this->resourceName);
            $controller->makeAllPresets(
                $this->pageStatus->dbconnection,
                $this->pageStatus->logger,
                new PublicSecurityChecker,
                new NullMailer('', '')
            );
            //if ( $this->bodyFile != '' ) return HtmlTemplateLoader::loadTemplate( TEMPLATES_DIRECTORY, $this->bodyFile );
            ob_start();
            $controller->showPage(); //would normally get printed to the screen/output to browser
            $output = ob_get_contents();
            ob_end_clean();
            return $output;
        } else {
            return '<p>Controller not defined</p>';
        }
    }

    /**
     * Implemented in order to mantain compatibilty with older implementations
     * return HTML code
     * @return string
     */
    function show(): string {
        return $this->getHTML();
    }

    /**
     * It creates the addToHead string iterating trough all BaseHTMLBlock contained
     * in the data structure
     */
    function addToHead(): string {
        if ( $this->addToHeadFile != '' ) return HtmlTemplateLoader::loadTemplate( TEMPLATES_DIRECTORY, $this->addToHeadFile );
        else return '';
    }

    /**
     * It creates the addToFoot string iterating trough all BaseHTMLBlock contained
     * in the data structure
     */
    function addToFoot(): string {
        if ( $this->addToFootFile != '' ) return HtmlTemplateLoader::loadTemplate( TEMPLATES_DIRECTORY, $this->addToFootFile );
        else return '';
    }

    function newAddToHeadOnce(): array {
        if ( $this->addToHeadOnceFile != '' ) return array( $this->resourceName, HtmlTemplateLoader::loadTemplate( TEMPLATES_DIRECTORY, $this->addToHeadOnceFile ) );
        else return array();
    }

    function newAddToFootOnce(): array {
        if ( $this->addToFootOnceFile != '' ) return array( $this->resourceName, HtmlTemplateLoader::loadTemplate( TEMPLATES_DIRECTORY, $this->addToFootOnceFile ) );
        else return array();
    }

}
