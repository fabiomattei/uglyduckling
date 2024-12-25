<?php

/**
 * Created by Fabio Mattei
 * Date: 02/11/2018
 * Time: 11:58
 */

namespace Fabiom\UglyDuckling\Framework\Blocks;

use Fabiom\UglyDuckling\Framework\Utils\HtmlTemplateLoader;

class BaseHTMLTable extends BaseHTMLBlock {

    protected $html;
    protected $title;
    protected $topActions;
    protected $bottomActions;

    function __construct() {
        parent::__construct();
        $this->html = '';
        $this->title = '';
    }

    function setTitle( string $title ) {
        $this->title = $title;
    }

    function setTopActions(string $topActions) {
        $this->topActions = $topActions;
    }

    function setBottomActions(string $bottomActions) {
        $this->bottomActions = $bottomActions;
    }

    function addRow() {
        $this->html .= HtmlTemplateLoader::loadTemplate( TEMPLATES_PATH,'BaseTable/openrow.html');
    }

    function closeRow() {
        $this->html .= HtmlTemplateLoader::loadTemplate( TEMPLATES_PATH,'BaseTable/closerow.html');
    }

    function addTHead() {
        $this->html .= HtmlTemplateLoader::loadTemplate( TEMPLATES_PATH,'BaseTable/openthead.html');
    }

    function closeTHead() {
        $this->html .= HtmlTemplateLoader::loadTemplate( TEMPLATES_PATH,'BaseTable/closethead.html');
    }

    function addTBody() {
        $this->html .= HtmlTemplateLoader::loadTemplate( TEMPLATES_PATH,'BaseTable/opentbody.html');
    }

    function closeTBody() {
        $this->html .= HtmlTemplateLoader::loadTemplate( TEMPLATES_PATH,'BaseTable/closetbody.html');
    }

    function addHeadLineColumn(string $value) {
        $this->html .= HtmlTemplateLoader::loadTemplateAndReplace(TEMPLATES_PATH, 
            array('${value}'),
            array($value),
            'BaseTable/headlinecolumn.html');
    }

    function addColumn(string $value) {
        $this->html .= HtmlTemplateLoader::loadTemplateAndReplace(TEMPLATES_PATH, 
            array('${value}'),
            array( htmlspecialchars( $value ) ),
            'BaseTable/tablecolumn.html');
    }

    function addUnfilteredColumn(string $value) {
        $this->html .= HtmlTemplateLoader::loadTemplateAndReplace(TEMPLATES_PATH, 
            array('${value}'),
            array( $value ),
            'BaseTable/tablecolumn.html');
    }

    function addColumnDate(string $value) {
        $this->html .= HtmlTemplateLoader::loadTemplateAndReplace(TEMPLATES_PATH, 
            array('${value}'),
            array( date( 'd/m/Y', strtotime( htmlspecialchars( $value ) ) ) ),
            'BaseTable/tablecolumn.html');
    }

    function addColumnDateTime(string $value) {
        $this->html .= HtmlTemplateLoader::loadTemplateAndReplace(TEMPLATES_PATH, 
            array('${value}'),
            array( date( 'H:i d/m/Y', strtotime( htmlspecialchars( $value ) ) ) ),
            'BaseTable/tablecolumn.html');
    }

    function addColumnNoFilters(string $value) {
        $this->html .= HtmlTemplateLoader::loadTemplateAndReplace(TEMPLATES_PATH, 
            array('${value}'),
            array( $value ),
            'BaseTable/tablecolumn.html');
    }

    function show(): string {
        return HtmlTemplateLoader::loadTemplateAndReplace(TEMPLATES_PATH, 
            array('${title}', '${html}', '${menutoptable}', '${menusubtable}'),
            array( $this->title, $this->html, $this->topActions, $this->bottomActions),
            'BaseTable/body.html');
    }

}