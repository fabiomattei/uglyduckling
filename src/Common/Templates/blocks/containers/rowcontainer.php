<?php

namespace templates\blocks\containers;

use core\blocks\BaseBlock;

class RowContainer extends BaseBlock {
	
	function __construct($width, $title, $blocks) {
		$this->width  = $width;
		$this->title  = $title; 
		$this->blocks = $blocks;
	}
	
    function show() {
		$out = '<div class="'.$this->width.'">
                    <div class="panel panel-default">';
		if ($this->title != '') {
		   $out .= '<div class="panel-heading">
				<h4 class="panel-title">'.$this->title.'</h4>
			</div>';
		}
		$out .= '<div class="panel-body">';

		if (isset($this->blocks)) {
			if (is_array($this->blocks)) {
				foreach ($this->blocks as $bl) {
					$out .= $bl->show();
				}
			} else {
				$out .= $this->blocks->show();
			}
		} 

        $out .= '</div>
                    </div>
                </div>';
        return $out; 
    }
	
    function addToHead() {
		$out = '';
		if (isset($this->blocks)) {
			if (is_array($this->blocks)) {
				foreach ($this->blocks as $bl) {
					$out .= $bl->addToHead();
				}
			} else {
				$out .= $this->blocks->addToHead();
			}
		}
        return $out;
    }
	
    function addToFoot() {
		$out = '';
		if (isset($this->blocks)) {
			if (is_array($this->blocks)) {
				foreach ($this->blocks as $bl) {
					$out .= $bl->addToFoot();
				}
			} else {
				$out .= $this->blocks->addToFoot();
			}
		}
        return $out;
    }
	
}