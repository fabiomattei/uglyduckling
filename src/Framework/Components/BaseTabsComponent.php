<?php

namespace Fabiom\UglyDuckling\Framework\Components;

class BaseTabsComponent extends BasePageComponent {

    protected array $tabs = [];

    public function renderPanels(): void {
        $this->renderTabsNode($this->tabs);
    }

    protected function allPanels(): array {
        $result = [];
        foreach ($this->tabs as $tab) {
            $result = array_merge($result, $this->collectComponentNodes($tab['panels']));
        }
        return $result;
    }

}
