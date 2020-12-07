<?php

/**
 * Created by Fabio Mattei
 * Date: 02/11/2018
 * Time: 11:58
 */

namespace Fabiom\UglyDuckling\Common\Blocks;

class BaseHTMLTable extends BaseHTMLBlock {

    protected $html;
    protected $title;
    protected $htmlTemplateLoader;

    function __construct() {
        $this->html = '';
        $this->title = '';
    }

    public function setHtmlTemplateLoader($htmlTemplateLoader) {
        $this->htmlTemplateLoader = $htmlTemplateLoader;
    }

    function setTitle( string $title ) {
        $this->title = $title;
    }

    function addRow() {
        $this->html .= $this->htmlTemplateLoader->loadTemplate('BaseTable/openrow.html');
    }

    function closeRow() {
        $this->html .= $this->htmlTemplateLoader->loadTemplate('BaseTable/closerow.html');
    }

    function addTHead() {
        $this->html .= $this->htmlTemplateLoader->loadTemplate('BaseTable/openthead.html');
    }

    function closeTHead() {
        $this->html .= $this->htmlTemplateLoader->loadTemplate('BaseTable/closethead.html');
    }

    function addTBody() {
        $this->html .= $this->htmlTemplateLoader->loadTemplate('BaseTable/opentbody.html');
    }

    function closeTBody() {
        $this->html .= $this->htmlTemplateLoader->loadTemplate('BaseTable/closetbody.html');
    }

    function addHeadLineColumn(string $value) {
        $this->html .= $this->htmlTemplateLoader->loadTemplateAndReplace(
            array('${value}'),
            array($value),
            'BaseTable/headlinecolumn.html');
    }

    function addColumn(string $value) {
        $this->html .= $this->htmlTemplateLoader->loadTemplateAndReplace(
            array('${value}'),
            array( htmlspecialchars( $value ) ),
            'BaseTable/tablecolumn.html');
    }

    function addUnfilteredColumn(string $value) {
        $this->html .= $this->htmlTemplateLoader->loadTemplateAndReplace(
            array('${value}'),
            array( $value ),
            'BaseTable/tablecolumn.html');
    }

    function addColumnDate(string $value) {
        $this->html .= $this->htmlTemplateLoader->loadTemplateAndReplace(
            array('${value}'),
            array( date( 'd/m/Y', strtotime( htmlspecialchars( $value ) ) ) ),
            'BaseTable/tablecolumn.html');
    }

    function addColumnDateTime(string $value) {
        $this->html .= $this->htmlTemplateLoader->loadTemplateAndReplace(
            array('${value}'),
            array( date( 'H:i d/m/Y', strtotime( htmlspecialchars( $value ) ) ) ),
            'BaseTable/tablecolumn.html');
    }

    function addColumnNoFilters(string $value) {
        $this->html .= $this->htmlTemplateLoader->loadTemplateAndReplace(
            array('${value}'),
            array( $value ),
            'BaseTable/tablecolumn.html');
    }

    function show(): string {
        return $this->htmlTemplateLoader->loadTemplateAndReplace(
            array('${title}', '${html}'),
            array( $this->title, $this->html),
            'BaseTable/body.html');
    }

}