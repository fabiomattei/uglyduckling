<?php

namespace Fabiom\UglyDuckling\Framework\Utils;

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
    static public function getRequestMethod(): string {
        return $_SERVER["REQUEST_METHOD"];
    }

    /**
     * Return $_SERVER['REQUEST_URI'] and apply $_SERVER['REQUEST_URI']
     * @return string
     */
    static public function getRequestURI(): string {
        return filter_var((isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : NULL), FILTER_SANITIZE_URL);
    }

    /**
     * Return $_SERVER["PHP_SELF"]
     * @return string
     */
    static public function getPhpSelf(): string {
        return $_SERVER["PHP_SELF"];
    }

    /**
     * Return $_SERVER['REMOTE_ADDR']
     * @return string
     */
    static public function getRemoteAddress(): string {
        return $_SERVER['REMOTE_ADDR'];
    }

    /**
     * Return $_SERVER['HTTP_USER_AGENT']
     * @return string
     */
    static public function getHttpUserAgent(): string {
        return $_SERVER['HTTP_USER_AGENT'];
    }

    /**
     * Return true if $_SERVER["REQUEST_METHOD"] contains "GET"
     * @return bool
     */
    static public function isGetRequest(): bool {
        return $_SERVER["REQUEST_METHOD"] == "GET";
    }

    /**
     * Return true if $_SERVER["REQUEST_METHOD"] contains "POST"
     * @return bool
     */
    static public function isPostRequest(): bool {
        return $_SERVER["REQUEST_METHOD"] == "POST";
    }

}
