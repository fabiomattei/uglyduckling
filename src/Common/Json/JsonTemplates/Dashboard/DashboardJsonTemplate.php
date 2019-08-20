<?php

/**
 * User: Fabio Mattei
 * Date: 22/07/2018
 * Time: 20:12
 */

namespace Fabiom\UglyDuckling\Common\Json\JsonTemplates\Dashboard;

use Fabiom\UglyDuckling\Common\Json\JsonTemplates\JsonTemplate;
use Fabiom\UglyDuckling\Common\Blocks\BaseHTMLDashboard;
use Fabiom\UglyDuckling\Common\Json\JsonTemplates\JsonTemplateFactory;

class DashboardJsonTemplate extends JsonTemplate {

    const blocktype = 'dashboard';

    private $jsonTemplateFactory;

    /**
     * Setting panelBuilder
     *
     * @param $panelBuilder
     */
    public function setPanelBuilder( JsonTemplateFactory $jsonTemplateFactory ) {
        $this->jsonTemplateFactory = $jsonTemplateFactory;
    }

    /**
     * It creates an HTMLBlock containing all information necessary in order to create
     * the actual HTML code
     *
     * @return BaseHTMLDashboard
     */
    public function createHTMLBlock(): BaseHTMLDashboard {
        // this first section of the code roun trough all defined panels for the specific
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
        $htmlDashboard->setHtmlTemplateLoader( $this->htmlTemplateLoader );

        foreach ($panelRows as $row) {
            $htmlDashboard->createNewRow();
            foreach ($row as $panel) {
                $htmlDashboard->addBlockToCurrentRow( $panel );
            }
        }

        return $htmlDashboard;
    }

}
