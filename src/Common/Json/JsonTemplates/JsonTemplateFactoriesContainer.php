<?php

/**
 * Created Fabio Mattei
 * Date: 2019-10-12
 * Time: 22:23
 */

namespace Fabiom\UglyDuckling\Common\Json\JsonTemplates;

use Fabiom\UglyDuckling\Common\Blocks\Button;
use Fabiom\UglyDuckling\Common\Blocks\CardHTMLBlock;
use Fabiom\UglyDuckling\Common\Database\DBConnection;
use Fabiom\UglyDuckling\Common\Database\QueryExecuter;
use Fabiom\UglyDuckling\Common\Json\JsonTemplates\QueryBuilder;
use Fabiom\UglyDuckling\Common\Json\JsonLoader;
use Fabiom\UglyDuckling\Common\Loggers\Logger;
use Fabiom\UglyDuckling\Common\Router\RoutersContainer;
use Fabiom\UglyDuckling\Common\Status\ApplicationBuilder;
use Fabiom\UglyDuckling\Common\Status\PageStatus;
use Fabiom\UglyDuckling\Common\Utils\HtmlTemplateLoader;
use Fabiom\UglyDuckling\Common\Wrappers\ServerWrapper;
use Fabiom\UglyDuckling\Common\Wrappers\SessionWrapper;
use Fabiom\UglyDuckling\Common\Blocks\BaseHTMLBlock;

class JsonTemplateFactoriesContainer {

    protected /* QueryExecuter */ $queryExecuter;
    protected /* QueryBuilder */ $queryBuilder;
    private /* array */ $factories;
    private /* ServerWrapper */ $serverWrapper;
    private /* array */ $parameters;
    private /* array */ $postparameters;
    private /* array */ $sessionparameters;
    private /* string */ $action;
    private /* HtmlTemplateLoader */ $htmlTemplateLoader;
    private /* JsonLoader */ $jsonloader;
    private /* ApplicationBuilder */ $applicationBuilder;
    private /* PageStatus */ $pageStatus;
    private $buttonBuilder;

    /**
     * JsonTemplateFactoriesContainer constructor.
     */
    public function __construct() {
        $this->factories = array();
        $this->queryExecuter = new QueryExecuter;
        $this->queryBuilder = new QueryBuilder;
        $this->buttonBuilder = new Button;
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
        // $panelBlock->setJsonTemplateFactoriesContainer();

        $panelBlock->setTitle($panel->title ?? '');
        $panelBlock->setWidth($panel->width ?? '3');
        $panelBlock->setHtmlTemplateLoader( $this->htmlTemplateLoader );

        $resource = $this->jsonloader->loadResource( $panel->resource );

        $panelBlock->setInternalBlockName( $resource->name ?? '' );
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
        $panelBlock->setInternalBlockName( $resource->name ?? '' );
        $panelBlock->setBlock($this->getHTMLBlock($resource));
        return $panelBlock;
    }

    /**
     * Given a specific json resource select between all JsonTemplateFactories
     * and return an instance of BaseHTMLBlock or a subclass of BaseHTMLBlock
     *
     * @param $resource
     * @return BaseHTMLBlock
     */
    public function getHTMLBlock( $resource ): BaseHTMLBlock {
        foreach ($this->factories as $factory) {
            if ( $factory->isResourceSupported( $resource ) ) {
                $factory->setResource($resource);
                $factory->setAction($this->action);
                return $factory->getHTMLBlock( $resource );
            }
        }

        return new BaseHTMLBlock;
    }

    /**
     * @return ApplicationBuilder
     */
    public function getApplicationBuilder(): ApplicationBuilder {
        return $this->applicationBuilder;
    }

    /**
     * @param ApplicationBuilder $applicationBuilder
     */
    public function setApplicationBuilder(ApplicationBuilder $applicationBuilder): void {
        $this->applicationBuilder = $applicationBuilder;
    }

    /**
     * @return PageStatus
     */
    public function getPageStatus(): PageStatus {
        return $this->pageStatus;
    }

    /**
     * @param PageStatus $applicationBuilder
     */
    public function setPageStatus(PageStatus $pageStatus): void {
        $this->pageStatus = $pageStatus;
    }

    /**
     * @return QueryExecuter
     */
    public function getQueryExecuter(): QueryExecuter {
        return $this->queryExecuter;
    }

    /**
     * @return QueryBuilder
     */
    public function getQueryBuilder(): QueryBuilder {
        return $this->queryBuilder;
    }

    /**
     * @return array
     */
    public function getFactories(): array {
        return $this->factories;
    }

    /**
     * @return mixed
     */
    public function getRouterContainer() {
        return $this->applicationBuilder->getRouterContainer();
    }

    /**
     * @return DBConnection
     */
    public function getDbconnection() {
        return $this->applicationBuilder->getDbconnection();
    }

    /**
     * @return SessionWrapper
     */
    public function getSessionWrapper() {
        return $this->pageStatus->getSessionWrapper() ;
    }

    /**
     * @return ServerWrapper
     */
    public function getServerWrapper() {
        return $this->pageStatus->getServerWrapper();
    }

    /**
     * @return mixed
     */
    public function getParameters() {
        return $this->parameters;
    }

    /**
     * @return mixed
     */
    public function getPostparameters() {
        return $this->postparameters;
    }

    /**
     * @return mixed
     */
    public function getSessionparameters() {
        return $this->sessionparameters;
    }

    /**
     * @return mixed
     */
    public function getAction() {
        return $this->action;
    }

    /**
     * @return mixed
     */
    public function getHtmlTemplateLoader() {
        return $this->applicationBuilder->getHtmlTemplateLoader();
    }

    /**
     * @return mixed
     */
    public function getJsonloader() {
        return $this->applicationBuilder->getJsonloader();
    }

    /**
     * @return mixed
     */
    public function getLinkBuilder() {
        return $this->applicationBuilder->getLinkBuilder();
    }

    /**
     * @return mixed
     */
    public function getLogger() {
        return $this->applicationBuilder->getLogger();
    }

    /**
     * @return mixed
     */
    public function getButtonBuilder() {
        return $this->buttonBuilder;
    }

    /**
     * @return mixed
     */
    public function getSetup() {
        return $this->applicationBuilder->getSetup();
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
     * Set the complete URL for the form action
     * @param action $action
     */
    public function setAction( string $action ): void {
        $this->action = $action;
    }

    /**
     * @param mixed $buttonBuilder
     */
    public function setButtonBuilder($buttonBuilder): void {
        $this->buttonBuilder = $buttonBuilder;
    }

}
