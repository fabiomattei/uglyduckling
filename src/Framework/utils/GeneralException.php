<?php

/* ==============================================================
 * This file contains the definition of an exception.
 * The exception stops the code execution and load a page called
 * errorpage.html that shows an error message
 * ============================================================== */

// GeneralException
class GeneralException extends Exception {
}

function generalExceptionHandler($exception) {
	// echo $exception;
	header( 'Location: '.BASEPATH );
	die();
}

set_exception_handler('generalExceptionHandler');
