<?php

namespace Fabiom\UglyDuckling\Common\Blocks;

use Fabiom\UglyDuckling\Common\Blocks\BaseHTMLTable;

class StaticTable extends BaseHTMLTable {

  private $buttons;

  function __construct() {
      parent::__construct();
      $this->buttons = '';
  }

  function show(): string {
    return '<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3"><h2>'.$this->title.'</h2><div class="btn-toolbar mb-2 mb-md-0"><div class="btn-group mr-2">'.$this->buttons.'</div></div></div><div class="table-responsive"><table class="table table-striped table-sm">'.$this->html.'</table></div>';
  }

  function addButton(string $label, string $url ) {
    $this->buttons .= '<a class="btn btn-sm btn-outline-secondary" href="'.$url.'">'.$label.'</a>';
  }
	
}
