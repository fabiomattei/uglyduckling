<?php

namespace Firststep\Common\Blocks;

use Firststep\Common\Blocks\BaseBlock;

class BaseMessages extends BaseBlock {
	
	function __construct() {
	    $this->info = '';
		$this->success = '';
		$this->warning = '';
		$this->error = '';
	}
	
    function show(): string {
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
	
	function setInfo( string $info ) {
		$this->info = $info;
	}
	
	function setSuccess( string $success ) {
		$this->success = $success;
	}
	
	function setWarning( string $warning ) {
		$this->warning = $warning;
	}
	
	function setError( string $error ) {
		$this->error = $error;
	}
	
}
