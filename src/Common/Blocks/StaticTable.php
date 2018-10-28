<?php

namespace Firststep\Common\Blocks;

use Firststep\Common\Blocks\BaseBlock;

class StaticTable extends BaseBlock {

  private $html;
  private $title;
  private $buttons;
  
  function __construct() {
    $this->html = '';
    $this->title = '';
    $this->buttons = '';
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
    return '<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3"><h2>'.$this->title.'</h2><div class="btn-toolbar mb-2 mb-md-0"><div class="btn-group mr-2">'.$this->buttons.'</div></div></div><div class="table-responsive"><table class="table table-striped table-sm">'.$this->html.'</table></div>';
  }

  function addButton(string $label, string $url ) {
    $this->buttons .= '<a class="btn btn-sm btn-outline-secondary" href="'.$url.'">'.$label.'</a>';
  }
	
}
