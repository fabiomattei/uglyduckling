<?php

namespace Firststep\Common\Request;

use Firststep\Common\Wrappers;

class ServerWrapper {
	
    public function getServerRequestMethod(): string {
        return $_SERVER['REQUEST_URI'];
    }

    public function getServerPhpSelf(): string {
        return $_SERVER["PHP_SELF"];
    }
	
    public function getServerRequestMethod(): string {
        return $_SERVER["REQUEST_METHOD"];
    }

    public function setServerRemoteAddress(): string {
        return $_SERVER['REMOTE_ADDR'];
    }

    public function getServerHttpUserAgent(): string {
        return $_SERVER['HTTP_USER_AGENT'];
    }
	
}
