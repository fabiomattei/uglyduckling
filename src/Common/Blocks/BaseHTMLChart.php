<?php

/**
 * Created by Fabio Mattei
 * Date: 01/11/18
 * Time: 9.43
 */

namespace Fabiom\UglyDuckling\Common\Blocks;

use Fabiom\UglyDuckling\Common\Blocks\BaseHTMLBlock;

class BaseHTMLChart extends BaseHTMLBlock {

    private $htmlBlockId;
    private $lables;
    private $dataset;
    private $structure;
    private $chartdataglue;
    private $glue;
    private $htmlTemplateLoader;

    function __construct() {
        $this->htmlBlockId = 'myChart';
        $this->lables = '';
        $this->dataset = '';
        $this->structure = \stdClass;
        $this->chartdataglue = array();
        $this->glue = array();
    }

    public function setHtmlTemplateLoader($htmlTemplateLoader) {
        $this->htmlTemplateLoader = $htmlTemplateLoader;
    }

    function setStructure($structure) {
        $this->structure = $structure;
    }

    function setChartDataGlue($chartDataGlue) {
        $this->chartdataglue = $chartDataGlue;
    }

    /**
     * Set the HTML block that is going to be used as block id
     *
     * @param string $htmlBlockId
     */
    public function setHtmlBlockId(string $htmlBlockId): void {
        $this->htmlBlockId = $htmlBlockId;
    }

    function setData($data) {
        print_r($data);
        foreach ( $data as $dt ) {
            foreach ($this->chartdataglue as $dg) {
                if(!isset($this->glue[$dg->placeholder])) $this->glue[$dg->placeholder] = array();
                $this->glue[$dg->placeholder][] = $dt->{$dg->sqlfield};
            }
        }
    }

    function addToHead(): string {
        return $this->htmlTemplateLoader->loadTemplateAndReplace(
            array(),
            array(),
            'Chartjs/addtohead.html');
    }

    function show(): string {
        $this->structure->data->labels = $this->glue['#labels'];
        // $this->structure->data->datasets[0]->data = $this->glue['#amounts'];
        foreach ($this->structure->data->datasets[0] as $dataset) {
            if ( isset($dataset->data) ) {
                if ( isset($this->glue[$dataset->data]) ) {
                    $dataset->data = $this->glue[$dataset->data];
                }
            }
        }
        $this->structure->data->datasets[0]->data = $this->glue['#amounts'];
        return $this->htmlTemplateLoader->loadTemplateAndReplace(
            array( '${htmlBlockId}', '${structure}' ),
            array( $this->htmlBlockId, json_encode( $this->structure ) ),
            'Chartjs/body.html');
    }

}
