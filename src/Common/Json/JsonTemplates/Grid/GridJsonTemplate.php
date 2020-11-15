<?php

/**
 * User: Fabio Mattei
 * Date: 15/10/2020
 * Time: 20:12
 */

namespace Fabiom\UglyDuckling\Common\Json\JsonTemplates\Dashboard;

use Fabiom\UglyDuckling\Common\Blocks\CardHTMLBlock;
use Fabiom\UglyDuckling\Common\Json\JsonTemplates\JsonTemplate;
use Fabiom\UglyDuckling\Common\Blocks\BaseHTMLDashboard;

class GridJsonTemplate extends JsonTemplate {

    const blocktype = 'grid';

    /**
     * It creates an HTMLBlock containing all information necessary in order to create
     * the actual HTML code
     *
     * @return BaseHTMLDashboard
     */
    public function createHTMLBlock(): BaseHTMLDashboard {
        $htmlTemplateLoader = $this->applicationBuilder->getHtmlTemplateLoader();

        // this first section of the code run trough all defined panels for the specific
        // dashboard and add each of them to the array $panelRows
        // I am separating panels by row
        $panelRows = array();

        foreach ($this->resource->panels as $panel) {
            // if there is not array of panels defined for that specific row I am going to create one
            if( !array_key_exists($panel->row, $panelRows) ) $panelRows[$panel->row] = array();
            // adding the panel section, taken from the dashboard json file, to array
            $panelRows[$panel->row][] = $panel;
        }

        $htmlDashboard = new BaseHTMLDashboard;
        $htmlDashboard->setHtmlTemplateLoader( $htmlTemplateLoader );

        foreach ($panelRows as $row) {
            $htmlDashboard->createNewRow();
            foreach ($row as $panel) {
                $htmlDashboard->addBlockToCurrentRow( $this->getPanel($panel) );
            }
        }

        return $htmlDashboard;
    }

    /**
     * Called in DashboardJsonTemplate
     *
     * @param $panel
     * @return CardHTMLBlock
     */
    function getPanel($panel) {
        $panelBlock = new CardHTMLBlock;

        $panelBlock->setTitle($panel->title ?? '');
        $panelBlock->setWidth($panel->width ?? '3');
        $panelBlock->setCssClass($panel->cssclass ?? '');
        $panelBlock->setHtmlTemplateLoader( $this->applicationBuilder->getHtmlTemplateLoader() );

        $resource = $this->applicationBuilder->loadResource( $panel->resource );

        $panelBlock->setInternalBlockName( $resource->name ?? '' );
        $panelBlock->setBlock($this->applicationBuilder->getHTMLBlock($resource));

        return $panelBlock;
    }

}
