<?php

/**
 * User: Fabio Mattei
 * Date: 15/10/2020
 * Time: 20:12
 */

namespace Fabiom\UglyDuckling\Framework\Json\JsonTemplates\Grid;

use Fabiom\UglyDuckling\Framework\Json\JsonTemplates\JsonDefaultTemplateFactory;
use Fabiom\UglyDuckling\Framework\Json\JsonTemplates\JsonTemplate;
use Fabiom\UglyDuckling\Framework\Blocks\BaseHTMLGrid;
use Fabiom\UglyDuckling\Framework\Routing\RouteTable;

class GridJsonTemplate extends JsonTemplate {

    const blocktype = 'grid';

    /**
     * It creates an HTMLBlock containing all information necessary in order to create
     * the actual HTML code
     *
     * @return BaseHTMLGrid
     */
    public function createHTMLBlock(): BaseHTMLGrid {
        // A panel whose resource the session's group can't open is dropped entirely,
        // not just skipped when rendering - so it's never part of the interface at all.
        $panels = array_values(array_filter(
            $this->resource->panels,
            fn($panel) => RouteTable::isVisible($panel->resource)
        ));

        $gridBlocks = [];
        foreach ($panels as $panel) {
            $gridBlocks[$panel->id] = JsonDefaultTemplateFactory::getHTMLBlock($this->resourcesIndex, $this->jsonResourceTemplates, $this->jsonTabTemplates, $this->pageStatus, $panel->resource);
        }

        $grid = new BaseHTMLGrid;
        $grid->setBlocks($gridBlocks);
        $grid->setPanels($panels);
        $grid->setCssClass($this->resource->cssclass);

        return $grid;
    }

}
