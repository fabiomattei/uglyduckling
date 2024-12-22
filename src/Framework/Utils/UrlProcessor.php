<?php

namespace Fabiom\UglyDuckling\Framework\Utils;



function setServerRequestURI( string $requestURI, string $pathToApp = '/' ) {
    $request2 = str_replace( ['.html', '.pdf', '.svg'], '',$requestURI );
    $request  = preg_replace( '/\?.*/', '', $request2 );

    if ( $request == '' ) throw new \Exception('General malfuction!!!');

    # removing the first '/' from the path
    $action = substr( $request, strlen($pathToApp) ); // explode( '/', $request );

    if (validate_string( $action )) {
        throw new \Exception('Illegal access to calculateSplittedURL!!! Unvalidated action: "' . $action . '" over request: ' . $requestURI);
    }

    return $action;
}
