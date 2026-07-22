<?php

namespace Fabiom\UglyDuckling\Framework\Components;

use Fabiom\UglyDuckling\Framework\Controllers\BaseController;

class BasePageComponent extends BaseController {

    use AllowedGroupsTrait;

    public string $addToHead = '';
    public string $addToFoot = '';

    public function __construct() {
        parent::__construct();
        $this->templateFile = 'template';
        $this->controllerPointer = $this;
        $this->appTitle = defined('APP_NAME') ? APP_NAME : '';
    }

    public function check_authorization_get_request(): bool {
        return $this->isGroupAllowed();
    }

    public function check_authorization_post_request(): bool {
        return $this->isGroupAllowed();
    }

    public function showPage(): void {
        if ($this->isGetRequest()) {
            $this->createCsrfToken();
            if (!$this->check_authorization_get_request()) {
                $this->redirectToPage('index.html');
                return;
            }
            $this->onGetRequest();
            $this->check_get_request();
        } else {
            if (!$this->check_authorization_post_request()) {
                $this->redirectToPage('index.html');
                return;
            }
            $this->dispatchPost();
            $this->onPostRequest();
        }
        $this->addToHead = $this->collectHead();
        $this->addToFoot = $this->collectFoot();
        $this->viewFile = 'src/Templates/Common/ComponentView.php';
        $this->loadTemplate();
    }

    /**
     * Called during GET request processing, after authorization and before validation.
     * Override in application subclasses to build navigation, menus, or other page scaffolding.
     */
    protected function onGetRequest(): void {}

    /**
     * Called during POST request processing, after dispatch.
     * Override in application subclasses to build navigation or other page scaffolding.
     */
    protected function onPostRequest(): void {}

    protected function post_request(): void {}

    public function renderPanels(): void {}

    public function collectHead(): string {
        $result = '';
        foreach ($this->allPanels() as $panel) {
            $child = new $panel['component']();
            $child->pageStatus = $this->pageStatus;
            $result .= $child->addToHead();
        }
        return $result;
    }

    public function collectFoot(): string {
        $result = '';
        foreach ($this->allPanels() as $panel) {
            $child = new $panel['component']();
            $child->pageStatus = $this->pageStatus;
            $result .= $child->addToFoot();
        }
        return $result;
    }

    protected function renderNode(array $node): void {
        $css = htmlspecialchars($node['cssclass'] ?? '');
        if (isset($node['component'])) {
            $child = new $node['component']();
            $child->pageStatus = $this->pageStatus;
            echo '<div class="' . $css . '">';
            $child->renderAsPanel();
            echo '</div>';
        } elseif (isset($node['embed'])) {
            // renderPanels()/allPanels() are defined on this same base class for both
            // BaseGridComponent and BaseTabsComponent, so embedding is type-agnostic and
            // recursive: a grid can embed a tabs page and vice versa, nested arbitrarily deep.
            $child = new $node['embed']();
            $child->pageStatus = $this->pageStatus;
            if ($child->check_authorization_get_request()) {
                echo '<div class="' . $css . '">';
                $child->renderPanels();
                echo '</div>';
            }
        } elseif (isset($node['panels'])) {
            echo '<div class="' . $css . '">';
            foreach ($node['panels'] as $subNode) {
                $this->renderNode($subNode);
            }
            echo '</div>';
        } elseif (isset($node['tabs'])) {
            echo '<div class="' . $css . '">';
            $this->renderTabsNode($node['tabs']);
            echo '</div>';
        }
    }

    protected function renderTabsNode(array $tabs): void {
        echo '<nav><div class="nav nav-tabs" role="tablist">';
        $first = true;
        foreach ($tabs as $tab) {
            $active = $first ? ' active' : '';
            echo '<button class="nav-link' . $active . '"'
               . ' id="' . htmlspecialchars($tab['id']) . '-tab"'
               . ' data-bs-toggle="tab"'
               . ' data-bs-target="#' . htmlspecialchars($tab['id']) . '"'
               . ' type="button" role="tab">'
               . htmlspecialchars($tab['label']) . '</button>';
            $first = false;
        }
        echo '</div></nav><div class="tab-content">';
        $first = true;
        foreach ($tabs as $tab) {
            $active = $first ? ' show active' : '';
            echo '<div class="tab-pane fade' . $active . '"'
               . ' id="' . htmlspecialchars($tab['id']) . '"'
               . ' role="tabpanel"'
               . ' aria-labelledby="' . htmlspecialchars($tab['id']) . '-tab">';
            foreach ($tab['panels'] as $subNode) {
                $this->renderNode($subNode);
            }
            echo '</div>';
            $first = false;
        }
        echo '</div>';
    }

    protected function collectComponentNodes(array $nodes): array {
        $result = [];
        foreach ($nodes as $node) {
            if (isset($node['component'])) {
                $result[] = $node;
            } elseif (isset($node['embed'])) {
                $embedded = new $node['embed']();
                $embedded->pageStatus = $this->pageStatus;
                if ($embedded->check_authorization_get_request()) {
                    $result = array_merge($result, $embedded->allPanels());
                }
            } elseif (isset($node['panels'])) {
                $result = array_merge($result, $this->collectComponentNodes($node['panels']));
            } elseif (isset($node['tabs'])) {
                foreach ($node['tabs'] as $tab) {
                    $result = array_merge($result, $this->collectComponentNodes($tab['panels']));
                }
            }
        }
        return $result;
    }

    protected function allPanels(): array {
        return [];
    }

    protected function onPostSuccess(): void {
        $this->redirectToPreviousPage();
    }

    private function dispatchPost(): void {
        $componentClass = $_POST['_component'] ?? null;
        if ($componentClass !== null) {
            foreach ($this->allPanels() as $panel) {
                if ($panel['component'] === $componentClass) {
                    $child = new $panel['component']();
                    $child->pageStatus = $this->pageStatus;
                    $child->handlePost();
                    if ($child->postSuccessMessage !== '') {
                        $this->setSuccess($child->postSuccessMessage);
                    }
                    if ($child->postSuccessUrl !== '') {
                        $this->redirectToPage($child->postSuccessUrl);
                    }
                    $this->onPostSuccess();
                    return;
                }
            }
        }
        if ($this->check_post_request()) {
            $this->post_request();
            $this->onPostSuccess();
        }
    }

}
