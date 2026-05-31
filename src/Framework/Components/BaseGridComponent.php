<?php

namespace Fabiom\UglyDuckling\Framework\Components;

class BaseGridComponent extends BasePageComponent {

    protected array $panels = [];

    public function renderPanels(): void {
        foreach ($this->panels as $node) {
            $this->renderNode($node);
        }
    }

    protected function allPanels(): array {
        return $this->collectComponentNodes($this->panels);
    }

}
