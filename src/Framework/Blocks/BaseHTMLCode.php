<?php

namespace Fabiom\UglyDuckling\Framework\Blocks;

use Fabiom\UglyDuckling\Framework\Utils\HtmlTemplateLoader;

/**
 * Class BaseHTMLUniform
 *
 * It is un unparemitrized block
 */
class BaseHTMLCode extends BaseHTMLBlock {

    /**
     * Necessary in order to load the HTML surrounding code
     */
    private $resourceName;
    private $bodyFile;
    private $addToHeadFile;
    private $addToFootFile;
    private $addToHeadOnceFile;
    private $addToFootOnceFile;
    private $addToFootOnce;

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

    /**
     * it return the HTML code for the web page built on data structure
     */
    function getHTML(): string {
        if ( $this->bodyFile != '' ) return HtmlTemplateLoader::loadTemplate( TEMPLATES_DIRECTORY, $this->bodyFile );
        else return '';
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
