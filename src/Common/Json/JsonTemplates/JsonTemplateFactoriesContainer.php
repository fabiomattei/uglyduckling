<?php

/**
 * Created Fabio Mattei
 * Date: 2019-10-12
 * Time: 22:23
 */

namespace Fabiom\UglyDuckling\Common\Json\JsonTemplates;

use Fabiom\UglyDuckling\Common\Blocks\CardHTMLBlock;
use Fabiom\UglyDuckling\Common\Database\DBConnection;
use Fabiom\UglyDuckling\Common\Json\JsonLoader;
use Fabiom\UglyDuckling\Common\Loggers\Logger;
use Fabiom\UglyDuckling\Common\Router\RoutersContainer;
use Fabiom\UglyDuckling\Common\Utils\HtmlTemplateLoader;

class JsonTemplateFactoriesContainer {

    private array $factories;
    private RoutersContainer $routerContainer;
    private DBConnection $dbconnection;
    private array $parameters;
    private array $postparameters;
    private array $sessionparameters;
    private string $action;
    private HtmlTemplateLoader $htmlTemplateLoader;
    private JsonLoader $jsonloader;
    private LinkBuilder $linkBuilder;
    private Logger $logger;

    /**
     * JsonTemplateFactoriesContainer constructor.
     */
    public function __construct() {
        $this->factories = array();
    }

    /**
     * Setting routerContainer object
     *
     * @param RoutersContainer $routerContainer
     */
    public function setRouter( RoutersContainer $routerContainer ) {
        $this->routerContainer = $routerContainer;
    }

    /**
     * @param JsonLoader $jsonloader
     */
    public function setJsonloader(JsonLoader $jsonloader): void {
        $this->jsonloader = $jsonloader;
    }

    /**
     * @param mixed $parameters
     */
    public function setParameters($parameters): void {
        $this->parameters = $parameters;
    }

    /**
     * @param mixed $parameters
     */
    public function setPostParameters($parameters): void {
        $this->postparameters = $parameters;
    }

    /**
     * @param mixed $parameters
     */
    public function setSessionParameters($parameters): void {
        $this->sessionparameters = $parameters;
    }

    /**
     * @param DBConnection $dbconnection
     */
    public function setDbconnection( DBConnection $dbconnection): void {
        $this->dbconnection = $dbconnection;
    }

    /**
     * Setting Html template loader
     *
     * @param HtmlTemplateLoader $htmlTemplateLoader
     */
    public function setHtmlTemplateLoader(HtmlTemplateLoader $htmlTemplateLoader) {
        $this->htmlTemplateLoader = $htmlTemplateLoader;
    }

    /**
     * @param Logger $logger
     * the $logger variable contains a logger for this class
     */
    public function setLogger( Logger $logger ): void {
        $this->logger = $logger;
    }

    /**
     * Set the complete URL for the form action
     * @param action $action
     */
    public function setAction( string $action ): void {
        $this->action = $action;
    }

    /**
     * @param LinkBuilder $linkBuilder
     */
    public function setLinkBuilder( LinkBuilder $linkBuilder ) {
        $this->linkBuilder = $linkBuilder;
    }

    /**
     * Add a factory to the factories container
     * @param $jsonTemplateFactory
     */
    public function addJsonTemplateFactory( $jsonTemplateFactory ) {
        $this->factories[] = $jsonTemplateFactory;
    }

    function getPanel($panel) {
        $panelBlock = new CardHTMLBlock;
        $panelBlock->setTitle($panel->title ?? '');
        $panelBlock->setWidth($panel->width ?? '3');
        $panelBlock->setHtmlTemplateLoader( $this->htmlTemplateLoader );

        $resource = $this->jsonloader->loadResource( $panel->resource );

        $panelBlock->setBlock($this->getHTMLBlock($resource));

        return $panelBlock;
    }

    /**
     * Return a panel containing an HTML Block built with data in the resource field
     *
     * The HTML block type depends from the resource->metadata->type field in the json strcture
     *
     * @param $resource
     * @return CardHTMLBlock
     */
    function getWidePanel( $resource ) {
        $panelBlock = new CardHTMLBlock;
        $panelBlock->setTitle('');
        $panelBlock->setWidth( '12');
        $panelBlock->setHtmlTemplateLoader( $this->htmlTemplateLoader );
        $panelBlock->setBlock($this->getHTMLBlock($resource));
        return $panelBlock;
    }

    public function getHTMLBlock( $resource ) {
        foreach ($this->factories as $factory) {
            if ( $factory->isResourceSupported( $resource ) ) {
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
