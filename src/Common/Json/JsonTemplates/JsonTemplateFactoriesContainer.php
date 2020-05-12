<?php

/**
 * Created Fabio Mattei
 * Date: 2019-10-12
 * Time: 22:23
 */

namespace Fabiom\UglyDuckling\Common\Json\JsonTemplates;

use Fabiom\UglyDuckling\Common\Blocks\Button;
use Fabiom\UglyDuckling\Common\Database\DBConnection;
use Fabiom\UglyDuckling\Common\Database\QueryExecuter;
use Fabiom\UglyDuckling\Common\Status\ApplicationBuilder;
use Fabiom\UglyDuckling\Common\Status\PageStatus;
use Fabiom\UglyDuckling\Common\Wrappers\ServerWrapper;
use Fabiom\UglyDuckling\Common\Wrappers\SessionWrapper;
use Fabiom\UglyDuckling\Common\Blocks\BaseHTMLBlock;

class JsonTemplateFactoriesContainer {

    protected /* QueryExecuter */ $queryExecuter;
    protected /* QueryBuilder */ $queryBuilder;
    private /* array */ $factories;
    private /* array */ $parameters;
    private /* array */ $postparameters;
    private /* string */ $action;
    private /* ApplicationBuilder */ $applicationBuilder;
    private /* PageStatus */ $pageStatus;

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

}
