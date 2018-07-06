<?php

namespace Firststep\Templates\Blocks\Menus;

use Firststep\Common\Blocks\BaseBlock;

class AdminMenu extends BaseBlock {
	
	function __construct( string $appname, string $active = 'home' ) {
		$this->appname = $appname;
		$this->active = $active;
	}
	
    function show(): string {
		$out = '
    <nav class="navbar navbar-toggleable-md navbar-inverse bg-inverse fixed-top">
      <a class="navbar-brand" href="' . BASEPATH . '">' . $this->appname . '</a>
      <ul class="nav navbar-nav">
        <li class="nav-item '.( $this->active == 'home' ? 'active' : '').'">
          <a class="nav-link" href="' . BASEPATH . 'public/index.html">Admin <span class="sr-only">(current)</span></a>
        </li>
        <li class="nav-item '.( $this->active == 'about' ? 'active' : '').'">
          <a class="nav-link" href="#">Admin</a>
        </li>
        <li class="nav-item '.( $this->active == 'contact' ? 'active' : '').'">
          <a class="nav-link" href="#">Admin</a>
        </li>
        <li class="nav-item '.( $this->active == 'login' ? 'active' : '').' pull-xs-right">
          <a class="nav-link" href="' . BASEPATH . 'public/login.html">Admin</a>
        </li>
      </ul>
    </nav>';
        return $out; 
    }
	
}
