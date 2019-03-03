<?php

/**
 * Created by Fabio Mattei
 * Date: 02/11/2018
 * Time: 11:58
 */

namespace Firststep\Common\Blocks;

class BaseTable extends BaseBlock {

    protected $html;
    protected $title;
    private $htmlTemplateLoader;

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
        $this->html .= $this->htmlTemplateLoader->loadTemplateAndReplace(
            array(),
            array(),
            'BaseTable/openrow.html');
    }

    function closeRow() {
        $this->html .= $this->htmlTemplateLoader->loadTemplateAndReplace(
            array(),
            array(),
            'BaseTable/closerow.html');
    }

    function addTHead() {
        $this->html .= $this->htmlTemplateLoader->loadTemplateAndReplace(
            array(),
            array(),
            'BaseTable/openthead.html');
    }

    function closeTHead() {
        $this->html .= $this->htmlTemplateLoader->loadTemplateAndReplace(
            array(),
            array(),
            'BaseTable/closethead.html');
    }

    function addTBody() {
        $this->html .= $this->htmlTemplateLoader->loadTemplateAndReplace(
            array(),
            array(),
            'BaseTable/opentbody.html');
    }

    function closeTBody() {
        $this->html .= $this->htmlTemplateLoader->loadTemplateAndReplace(
            array(),
            array(),
            'BaseTable/closetbody.html');
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
