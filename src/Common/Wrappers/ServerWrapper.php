<?php

namespace Firststep\Common\Wrappers;

class ServerWrapper {
	
    public function getRequestURI(): string {
        return $_SERVER['REQUEST_URI'];
    }

    public function getPhpSelf(): string {
        return $_SERVER["PHP_SELF"];
    }
	
    public function getRemoteAddress(): string {
        return $_SERVER['REMOTE_ADDR'];
    }

    public function getHttpUserAgent(): string {
        return $_SERVER['HTTP_USER_AGENT'];
    }
	
}
