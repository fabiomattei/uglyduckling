<?php

namespace Fabiom\UglyDuckling\Common\Wrappers;

/**
 * Class ServerWrapper
 * @package Fabiom\UglyDuckling\Common\Wrappers
 *
 * This class is a wrapper for $_SERVER system array
 */
class ServerWrapper {

    /**
     * Return: $_SERVER["REQUEST_METHOD"]
     * @return mixed
     */
    public function getRequestMethod(): string {
        return $_SERVER["REQUEST_METHOD"];
    }

    /**
     * Return $_SERVER['REQUEST_URI'] and apply $_SERVER['REQUEST_URI']
     * @return string
     */
    public function getRequestURI(): string {
        return $_SERVER['REQUEST_URI'];
        // return filter_var((isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : NULL), FILTER_SANITIZE_URL);
    }

    /**
     * Return $_SERVER["PHP_SELF"]
     * @return string
     */
    public function getPhpSelf(): string {
        return $_SERVER["PHP_SELF"];
    }

    /**
     * Return $_SERVER['REMOTE_ADDR']
     * @return string
     */
    public function getRemoteAddress(): string {
        return $_SERVER['REMOTE_ADDR'];
    }

    /**
     * Return $_SERVER['HTTP_USER_AGENT']
     * @return string
     */
    public function getHttpUserAgent(): string {
        return $_SERVER['HTTP_USER_AGENT'];
    }

    /**
     * Return true if $_SERVER["REQUEST_METHOD"] contains "GET"
     * @return bool
     */
    public function isGetRequest(): bool {
        return $_SERVER["REQUEST_METHOD"] == "GET";
    }

    /**
     * Return true if $_SERVER["REQUEST_METHOD"] contains "POST"
     * @return bool
     */
    public function isPostRequest(): bool {
        return $_SERVER["REQUEST_METHOD"] == "POST";
    }
	
}
