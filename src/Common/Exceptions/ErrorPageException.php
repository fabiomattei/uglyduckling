<?php

namespace Firststep\Common\Exceptions;

use Exception;

/**
 * Created fabio
 * Date: 08/01/2018
 * Time: 09:25
 */

class ErrorPageException extends Exception { }

/*

function generalExceptionHandler($exception) {
	// echo $exception;
	header( 'Location: '.BASEPATH );
	die();
}

set_exception_handler('generalExceptionHandler');

*/
