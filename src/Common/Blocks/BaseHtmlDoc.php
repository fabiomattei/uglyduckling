<?php

namespace Fabiom\UglyDuckling\Common\Blocks;

class BaseHtmlDoc extends BaseHTMLBlock {

    protected $html = '';

    public function setHtmlTemplateLoader($htmlTemplateLoader) {
        $this->htmlTemplateLoader = $htmlTemplateLoader;
    }

    function h1( string $text ) {
        $this->html .= '<h1>'.$text.'</h1>';
    }

    function h2( string $text ) {
        $this->html .= '<h2>'.$text.'</h2>';
    }

    function h3( string $text ) {
        $this->html .= '<h3>'.$text.'</h3>';
    }

    function h4( string $text ) {
        $this->html .= '<h4>'.$text.'</h4>';
    }

    function h5( string $text ) {
        $this->html .= '<h5>'.$text.'</h5>';
    }

    function h6( string $text ) {
        $this->html .= '<h6>'.$text.'</h6>';
    }

    function paragraph( string $text ) {
        $this->html .= '<p>'.$text.'</p>';
    }

    function openTable() {
        $this->html .= '<table>';
    }

    function closeTable() {
        $this->html .= '</table>';
    }

    function openRow() {
        $this->html .= '<tr>';
    }

    function closeRow() {
        $this->html .= '</tr>';
    }

    function th(string $text) {
        $this->html .= '<th>'.$text.'</th>';
    }

    function td(string $text) {
        $this->html .= '<td>'.$text.'</td>';
    }

    function openOl() {
        $this->html .= '<ol>';
    }

    function closeOl() {
        $this->html .= '</ol>';
    }

    function openUl() {
        $this->html .= '<ul>';
    }

    function closeUl() {
        $this->html .= '</ul>';
    }

    function li( $text ) {
        $this->html .= '<li>'.$text.'</li>';
    }

    function label( $text ) {
        $this->html .= '<label>'.$text.'</label>';
    }
    function input( $text ) {
        $this->html .= '<input>'.$text.'</input>';
    }

    function textarea( $text ) {
        $this->html .= '<textarea>'.$text.'</textarea>';
    }

    function select( $text ) {
        $this->html .= '<select><option selected="selected">'.$text.'</option></select>';
    }

    function show(): string {
        return $this->html;
    }

}