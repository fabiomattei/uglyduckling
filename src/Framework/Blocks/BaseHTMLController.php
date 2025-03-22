<?php

namespace Fabiom\UglyDuckling\Framework\Blocks;

use DeepBlue\HIRM\Chapters\Moc\Controllers\MocCreateList;
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
    private $bodyFile;
    private $addToHeadFile;
    private $addToFootFile;
    private $addToHeadOnceFile;
    private $addToFootOnce;

    private PageStatus $pageStatus;

    public function __construct() {
        parent::__construct();
        $this->resourceName = '';
        $this->bodyFile = '';
        $this->addToHeadFile = '';
        $this->addToFootFile = '';
        $this->addToHeadOnceFile = '';
        $this->addToFootOnce = '';
    }

    public function setResourceName( string $resourceName ) {
        $this->resourceName = $resourceName;
    }

    public function setBodyFile( string $bodyFile ) {
        $this->bodyFile = $bodyFile;
    }

    public function setAddToHeadFile( string $addToHeadFile ) {
        $this->addToHeadFile = $addToHeadFile;
    }

    public function setAddToFootFile( string $addToFootFile ) {
        $this->addToFootFile = $addToFootFile;
    }

    public function setAddToHeadOnceFile( string $addToHeadOnceFile ) {
        $this->addToHeadOnceFile = $addToHeadOnceFile;
    }

    public function setAddToFootOnceFile( string $addToFootOnce ) {
        $this->addToFootOnce = $addToFootOnce;
    }

    public function setPageStatus( PageStatus $pageStatus ) {
        $this->pageStatus = $pageStatus;
    }

    /**
     * it return the HTML code for the web page built on data structure
     */
    function getHTML(): string {
        //$controller = new $index_controllers[$controllerName];
        $controller = new MocCreateList;
        //$controller->setGroupsIndex( $index_groups );
        $controller->setGroupsIndex( [] );
        $controller->setPageStatus($this->pageStatus);
        $controller->setTemplateFile('emptyapptemplate');   // $this->templateFile = 'apptemplate';
        //$controller->setControllerName($controllerName);
        $controller->setControllerName('moccreate');
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
