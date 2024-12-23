<?php

namespace Fabiom\UglyDuckling\Framework\Blocks;

use Fabiom\UglyDuckling\Framework\Blocks\BaseHTMLBlock;

class BaseHTMLMessages extends BaseHTMLBlock {

	private $htmlTemplateLoader;
    public $info = '';
	public $success = '';
	public $warning = '';
	public $error = '';
	
	function __construct() {
        parent::__construct();
	}

	public function setHtmlTemplateLoader($htmlTemplateLoader) {
        $this->htmlTemplateLoader = $htmlTemplateLoader;
    }
	
    function show(): string {
		$out = '';
		if ($this->info != '') {
			$out.= $this->htmlTemplateLoader->loadTemplateAndReplace( array('${info}'), array($this->info), 'Messages/info.html');
		}
		if ($this->success != '') {
			$out.= $this->htmlTemplateLoader->loadTemplateAndReplace( array('${success}'), array($this->success), 'Messages/success.html');
		}
		if ($this->warning != '') {
			$out.= $this->htmlTemplateLoader->loadTemplateAndReplace( array('${warning}'), array($this->warning), 'Messages/warning.html');
		}
		if ($this->error != '') {
			$out.= $this->htmlTemplateLoader->loadTemplateAndReplace( array('${error}'), array($this->error), 'Messages/error.html');
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
