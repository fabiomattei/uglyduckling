<?php

namespace Fabiom\UglyDuckling\Common\Status;

use Fabiom\UglyDuckling\Common\Blocks\BaseHTMLBlock;
use Fabiom\UglyDuckling\Common\Blocks\BaseHTMLMessages;
use Fabiom\UglyDuckling\Common\Database\DBConnection;
use Fabiom\UglyDuckling\Common\Json\JsonLoader;
use Fabiom\UglyDuckling\Common\Json\JsonTemplates\JsonTemplateFactoriesContainer;
use Fabiom\UglyDuckling\Common\Json\JsonTemplates\LinkBuilder;
use Fabiom\UglyDuckling\Common\Loggers\Logger;
use Fabiom\UglyDuckling\Common\Redirectors\Redirector;
use Fabiom\UglyDuckling\Common\Router\RoutersContainer;
use Fabiom\UglyDuckling\Common\SecurityCheckers\SecurityChecker;
use Fabiom\UglyDuckling\Common\Setup\Setup;
use Fabiom\UglyDuckling\Common\Tags\BaseHTMLTag;
use Fabiom\UglyDuckling\Common\Tags\HTMLTagsFactory;
use Fabiom\UglyDuckling\Common\Utils\HtmlTemplateLoader;

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
    public /* HTMLTagsFactory */ $htmlTagsFactory;
    public /* JsonTemplateFactoriesContainer */ $jsonTemplateFactoriesContainer;
    public /* LinkBuilder */ $linkBuilder;
	public /* BaseMailer */ $mailer;

    /**
     * Gets the appropriate HTML tag from the tag factory
     *
     * @param $jsonStructure
     * @param PageStatus $pageStatus
     * @param ApplicationBuilder $applicationBuilder
     * @return BaseHTMLTag
     */
    public function getHTMLTag( $jsonStructure, PageStatus $pageStatus, ApplicationBuilder $applicationBuilder ): string {
        return $this->htmlTagsFactory->getHTMLTag( $jsonStructure, $pageStatus, $applicationBuilder );
    }

    /**
     * Given a specific json resource select between all JsonTemplateFactories
     * and return an instance of BaseHTMLBlock or a subclass of BaseHTMLBlock
     *
     * @param $resource
     * @return BaseHTMLBlock
     */
    public function getHTMLBlock( $resource ): BaseHTMLBlock {
        return $this->jsonTemplateFactoriesContainer->getHTMLBlock( $resource );
    }

    public function getAppNameForPageTitle(): string  {
        return $this->setup->getAppNameForPageTitle();
    }

    public function loadResource( $resourcename ) {
        return $this->jsonloader->loadResource($resourcename);
    }

    /**
     * @return RoutersContainer
     */
    public function getRouterContainer(): RoutersContainer {
        return $this->routerContainer;
    }

    /**
     * @param mixed $routerContainer
     */
    public function setRouterContainer(RoutersContainer $routerContainer): void {
        $this->routerContainer = $routerContainer;
    }

    /**
     * @return Setup
     */
    public function getSetup(): Setup {
        return $this->setup;
    }

    /**
     * @param mixed $setup
     */
    public function setSetup(Setup $setup): void {
        $this->setup = $setup;
    }

    /**
     * @return SecurityChecker
     */
    public function getSecurityChecker(): SecurityChecker {
        return $this->securityChecker;
    }

    /**
     * @param mixed $securityChecker
     */
    public function setSecurityChecker(SecurityChecker $securityChecker): void {
        $this->securityChecker = $securityChecker;
    }

    /**
     * @return DBConnection
     */
    public function getDbconnection(): DBConnection {
        return $this->dbconnection;
    }

    /**
     * @param DBConnection $dbconnection
     */
    public function setDbconnection(DBConnection $dbconnection): void {
        $this->dbconnection = $dbconnection;
    }

    /**
     * @return Redirector
     */
    public function getRedirector(): Redirector {
        return $this->redirector;
    }

    /**
     * @param Redirector $urlredirector
     */
    public function setRedirector(Redirector $redirector): void {
        $this->redirector = $redirector;
    }

    /**
     * @return JsonLoader
     */
    public function getJsonloader(): JsonLoader {
        return $this->jsonloader;
    }

    /**
     * @param JsonLoader $jsonloader
     */
    public function setJsonloader(JsonLoader $jsonloader): void {
        $this->jsonloader = $jsonloader;
    }

    /**
     * @return Logger
     */
    public function getLogger(): Logger {
        return $this->logger;
    }

    /**
     * @param Logger $logger
     */
    public function setLogger(Logger $logger): void {
        $this->logger = $logger;
    }

    /**
     * @return BaseHTMLMessages
     */
    public function getMessages(): BaseHTMLMessages {
        return $this->messages;
    }

    /**
     * @param BaseHTMLMessages $messages
     */
    public function setMessages(BaseHTMLMessages $messages): void {
        $this->messages = $messages;
    }

    /**
     * @return HtmlTemplateLoader
     */
    public function getHtmlTemplateLoader(): HtmlTemplateLoader {
        return $this->htmlTemplateLoader;
    }

    /**
     * @param HtmlTemplateLoader $htmlTemplateLoader
     */
    public function setHtmlTemplateLoader(HtmlTemplateLoader $htmlTemplateLoader): void {
        $this->htmlTemplateLoader = $htmlTemplateLoader;
    }

    /**
     * @return HTMLTagsFactory
     */
    public function getHtmlTagsFactory(): HTMLTagsFactory {
        return $this->htmlTagsFactory;
    }

    /**
     * @param HTMLTagsFactory $htmlTagsFactory
     */
    public function setHtmlTagsFactory(HTMLTagsFactory $htmlTagsFactory): void {
        $this->htmlTagsFactory = $htmlTagsFactory;
    }

    /**
     * @return JsonTemplateFactoriesContainer
     */
    public function getJsonTemplateFactoriesContainer(): JsonTemplateFactoriesContainer {
        return $this->jsonTemplateFactoriesContainer;
    }

    /**
     * @param JsonTemplateFactoriesContainer $jsonTemplateFactoriesContainer
     */
    public function setJsonTemplateFactoriesContainer(JsonTemplateFactoriesContainer $jsonTemplateFactoriesContainer): void {
        $this->jsonTemplateFactoriesContainer = $jsonTemplateFactoriesContainer;
    }

    /**
     * @return LinkBuilder
     */
    public function getLinkBuilder(): LinkBuilder {
        return $this->linkBuilder;
    }

    /**
     * @param LinkBuilder $linkBuilder
     */
    public function setLinkBuilder(LinkBuilder $linkBuilder): void {
        $this->linkBuilder = $linkBuilder;
    }
	
    /**
     * @return Mailer
     */
    public function getMailer() {
        return $this->mailer;
    }

    /**
     * @param Mailer $mailer
     */
    public function setMailer($mailer) {
        $this->mailer = $mailer;
    }

}
