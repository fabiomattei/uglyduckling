<?php

namespace Firststep\Common\Wrappers;

class ServerWrapper {
	
    public function getRequestMethod() {
        return $_SERVER["REQUEST_METHOD"];
    }

    public function getRequestURI(): string {
        return filter_var(isset($_SERVER['REQUEST_URI']) ? rtrim($_SERVER['REQUEST_URI'], '/') : NULL, FILTER_SANITIZE_URL);
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

    public function isGetRequest() {
        return $_SERVER["REQUEST_METHOD"] == "GET";
    }

    public function isPostRequest() {
        return $_SERVER["REQUEST_METHOD"] == "POST";
    }
	
}
