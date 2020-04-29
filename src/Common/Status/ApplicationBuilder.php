<?php

namespace Fabiom\UglyDuckling\Common\Status;

class ApplicationBuilder {

    public /* RoutersContainer */ $routerContainer;
    public /* Setup */ $setup;
    public /* SecurityChecker */ $securityChecker;
    public /* DBConnection */ $dbconnection;
    public /* Redirector */ $redirector;
    public /* JsonLoader */ $jsonloader;
    public /* Logger */ $logger;
    public /* BaseHTMLMessages */ $messages;
    public /* HtmlTemplateLoader */ $htmlTemplateLoader;
    public /* JsonTemplateFactoriesContainer */ $jsonTemplateFactoriesContainer;
    public /* LinkBuilder */ $linkBuilder;

    /**
     * @return mixed
     */
    public function getRouterContainer() {
        return $this->routerContainer;
    }

    /**
     * @param mixed $routerContainer
     */
    public function setRouterContainer($routerContainer): void {
        $this->routerContainer = $routerContainer;
    }

    /**
     * @return mixed
     */
    public function getSetup() {
        return $this->setup;
    }

    /**
     * @param mixed $setup
     */
    public function setSetup($setup): void {
        $this->setup = $setup;
    }

    /**
     * @return mixed
     */
    public function getSecurityChecker() {
        return $this->securityChecker;
    }

    /**
     * @param mixed $securityChecker
     */
    public function setSecurityChecker($securityChecker): void {
        $this->securityChecker = $securityChecker;
    }

    /**
     * @return mixed
     */
    public function getDbconnection() {
        return $this->dbconnection;
    }

    /**
     * @param mixed $dbconnection
     */
    public function setDbconnection($dbconnection): void {
        $this->dbconnection = $dbconnection;
    }

    /**
     * @return mixed
     */
    public function getRedirector() {
        return $this->redirector;
    }

    /**
     * @param mixed $urlredirector
     */
    public function setRedirector($redirector): void {
        $this->redirector = $redirector;
    }

    /**
     * @return mixed
     */
    public function getJsonloader() {
        return $this->jsonloader;
    }

    /**
     * @param mixed $jsonloader
     */
    public function setJsonloader($jsonloader): void {
        $this->jsonloader = $jsonloader;
    }

    /**
     * @return mixed
     */
    public function getLogger() {
        return $this->logger;
    }

    /**
     * @param mixed $logger
     */
    public function setLogger($logger): void {
        $this->logger = $logger;
    }

    /**
     * @return mixed
     */
    public function getMessages() {
        return $this->messages;
    }

    /**
     * @param mixed $messages
     */
    public function setMessages($messages): void {
        $this->messages = $messages;
    }

    /**
     * @return mixed
     */
    public function getHtmlTemplateLoader() {
        return $this->htmlTemplateLoader;
    }

    /**
     * @param mixed $htmlTemplateLoader
     */
    public function setHtmlTemplateLoader($htmlTemplateLoader): void {
        $this->htmlTemplateLoader = $htmlTemplateLoader;
    }

    /**
     * @return mixed
     */
    public function getJsonTemplateFactoriesContainer() {
        return $this->jsonTemplateFactoriesContainer;
    }

    /**
     * @param mixed $jsonTemplateFactoriesContainer
     */
    public function setJsonTemplateFactoriesContainer($jsonTemplateFactoriesContainer): void {
        $this->jsonTemplateFactoriesContainer = $jsonTemplateFactoriesContainer;
    }

    /**
     * @return mixed
     */
    public function getLinkBuilder() {
        return $this->linkBuilder;
    }

    /**
     * @param mixed $linkBuilder
     */
    public function setLinkBuilder($linkBuilder): void {
        $this->linkBuilder = $linkBuilder;
    }

}
