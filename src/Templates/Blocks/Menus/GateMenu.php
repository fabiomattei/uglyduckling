<?php

namespace Fabiom\UglyDuckling\Templates\Blocks\Menus;

use Fabiom\UglyDuckling\Common\Blocks\BaseHTMLBlock;

class GateMenu extends BaseHTMLBlock {

    public $appname;
    public $active;
	
	function __construct( string $appname, string $active = 'home' ) {
		$this->appname = $appname;
		$this->active = $active;
	}
	
    function show(): string {
		$out = '<a class="navbar-brand col-sm-3 col-md-2 mr-0" href="#">' . $this->appname . '</a>
      <input class="form-control form-control-dark w-100" type="text" placeholder="Search" aria-label="Search">
      <ul class="navbar-nav px-3">
        <li class="nav-item text-nowrap">
          <a class="nav-link" href="#">Sign out</a>
        </li>
      </ul>';
        return $out; 
    }
	
}
