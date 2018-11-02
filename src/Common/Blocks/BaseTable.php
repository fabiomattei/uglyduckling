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

    function __construct() {
        $this->html = '';
        $this->title = '';
    }

    function setTitle( string $title ) {
        $this->title = $title;
    }

    function addRow() {
        $this->html .= '<tr>';
    }

    function closeRow() {
        $this->html .= '</tr>';
    }

    function addTHead() {
        $this->html .= '<thead>';
    }

    function closeTHead() {
        $this->html .= '</thead>';
    }

    function addTBody() {
        $this->html .= '<tbody>';
    }

    function closeTBody() {
        $this->html .= '</tbody>';
    }

    function addHeadLineColumn(string $value) {
        $this->html .= '<th>'.$value.'</th>';
    }

    function addColumn(string $value) {
        $this->html .= '<td>'.htmlspecialchars( $value ).'</td>';
    }

    function addUnfilteredColumn(string $value) {
        $this->html .= '<td>'.$value.'</td>';
    }

    function addColumnDate(string $value) {
        $this->html .= '<td>'.date( 'd/m/Y', strtotime( htmlspecialchars( $value ) ) ).'</td>';
    }

    function addColumnDateTime(string $value) {
        $this->html .= '<td>'.date( 'H:i d/m/Y', strtotime( htmlspecialchars( $value ) ) ).'</td>';
    }

    function addColumnNoFilters(string $value) {
        $this->html .= '<td>'.$value.'</td>';
    }

    function show(): string {
        return '<h2>'.$this->title.'</h2><table class="table table-striped table-sm">'.$this->html.'</table>';
    }

}
