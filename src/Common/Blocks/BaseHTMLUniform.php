<?php

namespace Fabiom\UglyDuckling\Common\Blocks;

/**
 * Class BaseHTMLUniform
 *
 * It is un unparemitrized block
 */
class BaseHTMLUniform extends BaseHTMLBlock {

    /**
     * Necessary in order to load the HTML surrounding code
     */
    private $htmlTemplateLoader;
    private $resourceName;
    private $bodyFile;
    private $addToHeadFile;
    private $addToFootFile;
    private $addToHeadOnceFile;
    private $addToFootOnce;

    public function __controller() {
    	$this->resourceName = '';
    	$this->bodyFile = '';
    	$this->addToHeadFile = '';
    	$this->addToFootFile = '';
    	$this->addToHeadOnceFile = '';
    	$this->addToFootOnce = '';
    }

    public function setHtmlTemplateLoader($htmlTemplateLoader) {
        $this->htmlTemplateLoader = $htmlTemplateLoader;
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
        return $this->htmlTemplateLoader->loadTemplate( $this->bodyFile );
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
        return $this->htmlTemplateLoader->loadTemplate( $this->addToHeadFile );
    }

    /** 
     * It creates the addToFoot string iterating trough all BaseHTMLBlock contained
     * in the data structure
     */
    function addToFoot(): string {
        return $this->htmlTemplateLoader->loadTemplate( $this->addToFootFile );
    }

    function newAddToHeadOnce(): array {
        return array( $this->resourceName, return $this->htmlTemplateLoader->loadTemplate( $this->addToHeadOnceFile ) );
    }

    function newAddToFootOnce(): array {
        return array( $this->resourceName, return $this->htmlTemplateLoader->loadTemplate( $this->addToFootOnceFile ) );
    }

}
