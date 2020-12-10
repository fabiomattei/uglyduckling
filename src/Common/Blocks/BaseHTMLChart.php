<?php

/**
 * Created by Fabio Mattei
 * Date: 01/11/18
 * Time: 9.43
 */

namespace Fabiom\UglyDuckling\Common\Blocks;

use Fabiom\UglyDuckling\Common\Blocks\BaseHTMLBlock;

class BaseHTMLChart extends BaseHTMLBlock {

    protected $htmlBlockId;
    protected $lables;
    protected $dataset;
    protected $structure;
    protected $chartdataglue;
    protected $glue;
    protected $htmlTemplateLoader;
	protected $width;
	protected $height;

    function __construct() {
        $this->htmlBlockId = 'myChart';
        $this->lables = '';
        $this->dataset = '';
        $this->structure = new \stdClass;
        $this->chartdataglue = array();
        $this->glue = array();
		$this->width = '400';
		$this->height = '400';
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
	
    function setWidth($width) {
        $this->width = $width;
    }
	
    function setHeight($height) {
        $this->height = $height;
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
		if (isset($this->glue['#amounts2'])) { $this->structure->data->datasets[1]->data = $this->glue['#amounts2']; }
        return $this->htmlTemplateLoader->loadTemplateAndReplace(
            array( '${htmlBlockId}', '${structure}', '${width}', '${height}' ),
            array( $this->htmlBlockId, json_encode( $this->structure ), $this->width, $this->height ),
            'Chartjs/body.html');
    }

}
