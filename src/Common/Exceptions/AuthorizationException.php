<?php

namespace Fabiom\UglyDuckling\Common\Exceptions;

/**
 * Created fabio
 * Date: 08/01/2018
 * Time: 09:25
 */

class AuthorizationException extends \Exception { }

/*

function generalExceptionHandler($exception) {
	// echo $exception;
	header( 'Location: '.BASEPATH );
	die();
}

set_exception_handler('generalExceptionHandler');

*/
