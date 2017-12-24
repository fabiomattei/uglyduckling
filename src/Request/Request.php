<?php

namespace Firststep\Request;

// use templates\blocks\message\Messages;
// use core\libs\gump\GUMP;

class Request {
	
	function __construct() {
		$this->msginfo = '';
		$logged_in = false;
	}
	
	/*
	 * Container for variable $_SESSION['msginfo'];
	 */
	public function setMsgInfo( $msginfo ) {
		$this->msginfo = $msginfo;
	}
		
	public function getMsgInfo() {
		return $this->msginfo;
	}

}
