<?php

namespace Fabiom\UglyDuckling\Framework\Components;

class BaseTabsComponent extends BasePageComponent {

    // Each entry: ['id' => '…', 'label' => '…', 'panels' => […nodes…]]
    // Each node in 'panels': ['cssclass'=>'…','component'=>Class]
    //                     or ['cssclass'=>'…','panels'=>[…nodes…]]
    //                     or ['cssclass'=>'…','embed'=>BasePageComponentSubclass] to nest another routable page/dashboard (may itself be a BaseGridComponent or BaseTabsComponent)
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
