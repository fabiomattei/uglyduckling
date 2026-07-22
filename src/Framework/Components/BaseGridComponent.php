<?php

namespace Fabiom\UglyDuckling\Framework\Components;

class BaseGridComponent extends BasePageComponent {

    // Each node: ['cssclass'=>'…','component'=>Class]
    //         or ['cssclass'=>'…','panels'=>[…nodes…]]
    //         or ['cssclass'=>'…','tabs'=>[['id'=>'…','label'=>'…','panels'=>[…nodes…]],…]]
    //         or ['cssclass'=>'…','embed'=>BasePageComponentSubclass] to nest another routable page/dashboard
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
