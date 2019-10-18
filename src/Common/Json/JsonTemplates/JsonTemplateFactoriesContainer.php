<?php

/**
 * Created Fabio Mattei
 * Date: 2019-10-12
 * Time: 22:23
 */

namespace Fabiom\UglyDuckling\Common\Json\JsonTemplates;

class JsonTemplateFactoriesContainer {

    private $factories;
    private $routerContainer;
    private $dbconnection;
    private $parameters;
    private $postparameters;
    private $sessionparameters;
    private $action;
    private $htmlTemplateLoader;
    private $jsonloader;
    private $linkBuilder;
    private $logger;

    /**
     * JsonTemplateFactoriesContainer constructor.
     */
    public function __construct() {
        $this->factories = array();
    }

    /**
     * Setting routerContainer object
     *
     * @param $routerContainer
     */
    public function setRouter( $routerContainer ) {
        $this->routerContainer = $routerContainer;
    }

    /**
     * @param mixed $jsonloader
     */
    public function setJsonloader($jsonloader) {
        $this->jsonloader = $jsonloader;
    }

    /**
     * @param mixed $parameters
     */
    public function setParameters($parameters) {
        $this->parameters = $parameters;
    }

    /**
     * @param mixed $parameters
     */
    public function setPostParameters($parameters) {
        $this->postparameters = $parameters;
    }

    /**
     * @param mixed $parameters
     */
    public function setSessionParameters($parameters) {
        $this->sessionparameters = $parameters;
    }

    /**
     * @param mixed $dbconnection
     */
    public function setDbconnection($dbconnection) {
        $this->dbconnection = $dbconnection;
    }

    /**
     * Setting Html template loader
     *
     * @param $htmlTemplateLoader
     */
    public function setHtmlTemplateLoader($htmlTemplateLoader) {
        $this->htmlTemplateLoader = $htmlTemplateLoader;
    }

    /**
     * @param mixed $logger
     * the $logger variable contains a logger for this class
     */
    public function setLogger( $logger ) {
        $this->logger = $logger;
    }

    /**
     * Set the complete URL for the form action
     * @param action $action
     */
    public function setAction( string $action ) {
        $this->action = $action;
    }

    public function setLinkBuilder( $linkBuilder ) {
        $this->linkBuilder = $linkBuilder;
    }

    /**
     * Add a factory to the factories container
     * @param $jsonTemplateFactory
     */
    public function addJsonTemplateFactory( $jsonTemplateFactory ) {
        $this->factories[] = $jsonTemplateFactory;
    }

    public function getHTMLBlock( $resource ) {
        foreach ($this->factories as $factory) {
            if ($factory->isResourceSupported( $resource )) {
                $factory->setHtmlTemplateLoader($this->htmlTemplateLoader);
                $factory->setJsonloader($this->jsonloader);
                $factory->setRouter($this->routerContainer);
                $factory->setResource($resource);
                $factory->setParameters($this->parameters);
                $factory->setDbconnection($this->dbconnection);
                $factory->setJsonTemplateFactoriesContainer($this);
                $factory->setLogger($this->logger);
                return $factory->getHTMLBlock( $resource );
            }
        }
    }
}
