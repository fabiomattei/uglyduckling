<?php

/**
 * User: Fabio Mattei
 * Date: 22/07/2018
 * Time: 20:12
 */

namespace Fabiom\UglyDuckling\Common\Json\JsonTemplates\Dashboard;

use Fabiom\UglyDuckling\Common\Json\JsonTemplates\JsonTemplate;

class DashboardJsonTemplate extends JsonTemplate {

    const blocktype = 'dashboard';

    public function createHTMLBlock() {
		$fieldRows = array();

        foreach ($this->resource->panels as $panel) {
            if( !array_key_exists($panel->row, $fieldRows) ) $fieldRows[$panel->row] = array();
            $fieldRows[$panel->row][] = $panel;
        }

        $rowcontainer = array();

        foreach ($fieldRows as $row) {
            $rowBlock = new RowHTMLBlock;
            $rowBlock->setHtmlTemplateLoader( $this->htmlTemplateLoader );
            foreach ($row as $panel) {
                $rowBlock->addBlock( $this->panelBuilder->getPanel($panel) );
            }
            $rowcontainer[] = $rowBlock;
        }

        return $rowcontainer[];
    }

}
