<?php

/**
 * Created by Fabio Mattei
 * Date: 01/11/18
 * Time: 9.43
 */

namespace Fabiom\UglyDuckling\Framework\Blocks;

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
	protected $actiononclick;

    function __construct() {
        parent::__construct();
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

    function setApplicationBuilder($applicationBuilder) {
        $this->applicationBuilder = $applicationBuilder;
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
        $this->glue = $data;
    }

    function addToHead(): string {
        return $this->htmlTemplateLoader->loadTemplate('Chartjs/addtohead.html');
    }

    function show(): string {
        $this->structure = $this->lookForTagsToSobstitute($this->structure);

		return HtmlTemplateLoader::loadTemplateAndReplace(TEMPLATES_PATH, 
            array( '${htmlBlockId}', '${structure}', '${width}', '${height}' ),
            array( $this->htmlBlockId, json_encode( $this->structure ), $this->width, $this->height ),
            'Chartjs/body.html');
    }

    /**
     * This method iterates recursively in the json structure and
     * make a substitution of the placeholders with the arrays
     * created using data coming from the query
     *
     * @param $source
     * @return mixed
     */
    function lookForTagsToSobstitute( $source ) {
        $keys = array_keys($this->glue);
        foreach ( $source as $key => $value ) {
            if ( is_string( $value ) ) {
                if ( in_array($value, $keys) ) {
                    $source->{$key} = $this->glue[$value];
                }
            }
            if ( is_object( $value ) ) {
                $this->lookForTagsToSobstitute( $value );
            }
            if ( is_array( $value ) ) {
                $this->lookForTagsToSobstitute( $value );
            }
        }
        return $source;
    }

}
