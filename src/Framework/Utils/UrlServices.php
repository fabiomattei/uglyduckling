<?php

namespace Fabiom\UglyDuckling\Framework\Utils;

class UrlServices {

    public static function extractActionName( string $requestURI, string $pathToApp = '/' ) {
        $request2 = str_replace( ['.html', '.pdf', '.svg'], '',$requestURI );
        $request  = preg_replace( '/\?.*/', '', $request2 );

        if ( $request == '' ) throw new \Exception('General malfuction!!!');

        # removing the first '/' from the path
        $action = substr( $request, strlen($pathToApp) ); // explode( '/', $request );

        if (!UrlServices::validate_string( $action )) {
            throw new \Exception('Illegal access to calculateSplittedURL!!! Unvalidated action: "' . $action . '" over request: ' . $requestURI);
        }

        return $action;
    }

    public static function getRequestURI(): string {
        return ServerWrapper::getRequestURI();
    }

    public static function validate_string( $string_var ) {
        if ( strlen( $string_var ) == 0 ) return true;
        if ( strlen( $string_var ) > 40 ) return false;
        if ( ctype_alnum( $string_var ) ) return true;
        return false;
    }

}
