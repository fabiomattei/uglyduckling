<?php

namespace templates\blocks\message;

use core\blocks\BaseBlock;

class Messages extends BaseBlock {
	
	function __construct() {
	    $this->info = '';
		$this->success = '';
		$this->warning = '';
		$this->error = '';
	}
	
    function show() {
		$out = '';
		if ($this->info != '') {
			$out.= '<div class="alert alert-info">'.$this->info.'</div>';
		}
		if ($this->success != '') {
			$out.= '<div class="alert alert-success">'.$this->success.'</div>';
		}
		if ($this->warning != '') {
			$out.= '<div class="alert alert-warning">'.$this->warning.'</div>';
		}
		if ($this->error != '') {
			$out.= '<div class="alert alert-danger">'.$this->error.'</div>';
		}
        return $out;
    }
	
	function setInfo($info) {
		$this->info = $info;
	}
	
	function setSuccess($success) {
		$this->success = $success;
	}
	
	function setWarning($warning) {
		$this->warning = $warning;
	}
	
	function setError($error) {
		$this->error = $error;
	}
	
}