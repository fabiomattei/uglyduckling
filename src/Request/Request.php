<?php

namespace Firststep\Request;

// use templates\blocks\message\Messages;
// use core\libs\gump\GUMP;

class Request {

    public $msgInfo = '';
    public $loggedIn = false;
	
	function __construct() {
		$this->msgInfo = '';
        $this->loggedIn = false;
	}

    /**
     * @return string
     */
    public function getMsgInfo(): string {
        return $this->msgInfo;
    }

    /**
     * @param string $msgInfo
     * Container for variable $_SESSION['msginfo'];
     */
    public function setMsgInfo(string $msgInfo) {
        $this->msgInfo = $msgInfo;
    }

    /**
     * @return bool
     */
    public function isLoggedIn(): bool {
        return $this->loggedIn;
    }

    /**
     * @param bool $loggedIn
     */
    public function setLoggedIn(bool $loggedIn) {
        $this->loggedIn = $loggedIn;
    }

}
