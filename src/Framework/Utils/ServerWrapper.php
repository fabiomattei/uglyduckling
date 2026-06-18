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
     * Return the real client IP address.
     * When behind a trusted reverse proxy, reads the first IP from
     * HTTP_X_FORWARDED_FOR. Falls back to REMOTE_ADDR.
     * Trusted proxy CIDRs can be set via the TRUSTED_PROXIES env var
     * as a comma-separated list (e.g. "127.0.0.1,10.0.0.0/8").
     */
    static public function getRemoteAddress(): string {
        $remoteAddr = $_SERVER['REMOTE_ADDR'] ?? '';

        $trustedProxies = array_filter(array_map('trim', explode(',', getenv('TRUSTED_PROXIES') ?: '')));

        if ( !empty($trustedProxies) && self::ipMatchesCidrs($remoteAddr, $trustedProxies) ) {
            if ( !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ) {
                $ips = array_map('trim', explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']));
                $clientIp = $ips[0];
                if ( filter_var($clientIp, FILTER_VALIDATE_IP) ) {
                    return $clientIp;
                }
            }
        }

        return $remoteAddr;
    }

    static private function ipMatchesCidrs( string $ip, array $cidrs ): bool {
        foreach ($cidrs as $cidr) {
            if ( strpos($cidr, '/') === false ) {
                if ( $ip === $cidr ) return true;
            } else {
                [ $subnet, $bits ] = explode('/', $cidr);
                $mask = ~((1 << (32 - (int)$bits)) - 1);
                if ( (ip2long($ip) & $mask) === (ip2long($subnet) & $mask) ) return true;
            }
        }
        return false;
    }

    /**
     * Return $_SERVER['HTTP_USER_AGENT']
     * @return string
     */
    static public function getHttpUserAgent(): string {
        return $_SERVER['HTTP_USER_AGENT'] ?? '';
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
