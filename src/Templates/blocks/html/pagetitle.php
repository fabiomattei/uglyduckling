<?php

namespace templates\blocks\html;

use core\blocks\BaseBlock;

class PageTitle extends BaseBlock {
	
	function __construct($title, $span = '12') {
	    $this->title = $title;
		$this->span = $span;
	}
	
    function show() {
        return '<div class="col-xs-'.$this->span.'"><h3>'.$this->title.'</h3></div>'; 
    }
	
}
