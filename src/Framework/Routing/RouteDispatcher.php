<?php

namespace Fabiom\UglyDuckling\Framework\Routing;

use Fabiom\UglyDuckling\Framework\Controllers\StaticPageController;
use Fabiom\UglyDuckling\Framework\DataBase\DBConnection;
use Fabiom\UglyDuckling\Framework\Loggers\Logger;
use Fabiom\UglyDuckling\Framework\Mailer\BaseMailer;
use Fabiom\UglyDuckling\Framework\SecurityCheckers\SecurityChecker;
use Fabiom\UglyDuckling\Framework\Utils\Config;
use Fabiom\UglyDuckling\Framework\Utils\PageStatus;
use Fabiom\UglyDuckling\Framework\Utils\ServerWrapper;
use Fabiom\UglyDuckling\Framework\Utils\UrlServices;

/**
 * Resolves the current request's slug (via RouteTable) to the one controller or
 * component class registered for it, wires it the same way index.php wires any
 * other controller, and dispatches to it. Falls back to a shared 404 page on no match.
 */
final class RouteDispatcher {

    public function __construct(
        private DBConnection $dbconnection,
        private Logger $logger,
        private SecurityChecker $securityChecker,
        private BaseMailer $mailer,
        private PageStatus $pageStatus,
        private array $groupsIndex,
        private string $notFoundTemplateFile,
        private string $notFoundViewFile,
    ) {}

    public function dispatch(): void {
        try {
            $route = RouteTable::forSlug(
                UrlServices::extractActionName(UrlServices::getRequestURI(), Config::get('PATH_TO_APP', '/'))
            );
        } catch (\InvalidArgumentException) {
            $this->dispatchNotFound();
            return;
        }

        if (isset($route['controller'])) {
            $this->dispatchController($route['controller']);
            return;
        }

        $this->dispatchComponent($route['component']);
    }

    private function dispatchController(string $controllerClass): void {
        $controller = new $controllerClass();
        $controller->setPageStatus($this->pageStatus);
        $controller->setGroupsIndex($this->groupsIndex);
        $controller->makeAllPresets($this->dbconnection, $this->logger, $this->securityChecker, $this->mailer);
        $controller->showPage();
    }

    private function dispatchComponent(string $componentClass): void {
        $component = new $componentClass();
        $component->pageStatus = $this->pageStatus;
        if (ServerWrapper::isGetRequest()) {
            $component->renderAsPanel();
        } else {
            $component->handlePost();
        }
    }

    private function dispatchNotFound(): void {
        http_response_code(404);
        $notFound = new StaticPageController($this->notFoundTemplateFile, $this->notFoundViewFile);
        $notFound->makeAllPresets($this->dbconnection, $this->logger, $this->securityChecker, $this->mailer);
        $notFound->showPage();
    }

}
