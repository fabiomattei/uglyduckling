<?php

namespace Fabiom\UglyDuckling\Common\Blocks;

use Fabiom\UglyDuckling\Common\Blocks\BaseHTMLBlock;
use Fabiom\UglyDuckling\Common\Json\JsonLoader;
use Fabiom\UglyDuckling\Common\Mailer\NullMailer;
use Fabiom\UglyDuckling\Common\SecurityCheckers\PublicSecurityChecker;
use Fabiom\UglyDuckling\Common\Status\PageStatus;
use Fabiom\UglyDuckling\Common\Utils\HtmlTemplateLoader;

class BaseHTMLController extends BaseHTMLBlock
{

    /**
     * Necessary in order to load the HTML surrounding code
     */
    private $resourceName;
    private $className;
    private $templateFile;

    private PageStatus $pageStatus;
    private HtmlTemplateLoader $htmlTemplateLoader;
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

    public function setHtmlTemplateLoader($htmlTemplateLoader) {
        $this->htmlTemplateLoader = $htmlTemplateLoader;
    }


    /**
     * it return the HTML code for the web page
     */
    function getHTML(): string {
        if (class_exists($this->className)) {
            $controller = new $this->className;
        } else {
            $controller = null;
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
        if ( $this->addToHeadFile != '' ) return $this->htmlTemplateLoader->loadTemplate( $this->addToHeadFile );
        else return '';
    }

    /**
     * It creates the addToFoot string iterating trough all BaseHTMLBlock contained
     * in the data structure
     */
    function addToFoot(): string {
        if ( $this->addToFootFile != '' ) return $this->htmlTemplateLoader->loadTemplate( $this->addToFootFile );
        else return '';
    }

    function newAddToHeadOnce(): array {
        if ( $this->addToHeadOnceFile != '' ) return array( $this->resourceName, $this->htmlTemplateLoader->loadTemplate( $this->addToHeadOnceFile ) );
        else return array();
    }

    function newAddToFootOnce(): array {
        if ( $this->addToFootOnceFile != '' ) return array( $this->resourceName, $this->htmlTemplateLoader->loadTemplate( $this->addToFootOnceFile ) );
        else return array();
    }

}