<?php

/**
 * @param $request             a string containing the request
 *
 * @return array               an array containing the results
 *
 * @throws GeneralException    in case of empty request
 *
 * Prende una stringa e la divide nelle sue parti.
 * Restituisce poi le parti ottenute attraverso un array.
 *
 * Es. 'folder-subfolder/action/par1/par2/par3'
 * Diventa array( 'folder', 'subfolder', 'action', array( 'par1', 'par2', 'par3' ) )
 */
function spliturl( $request ) {
	
	if ( $request == '' ) throw new GeneralException('General malfuction!!!');
	
	#split the string by '/'
	$params = explode( '/', $request );

	$folder = $params[0];
	$subfolder = '';
	if ( strpos( $folder, '-' ) !== FALSE ) {
		$newfamily = explode( '-', $folder );
		//print_r($newfamily);
		$folder    = $newfamily[0];
		$subfolder = $newfamily[1];
	}
	$action = $params[1];
	$parameters = array();
	if ( isset( $params[2] ) ) { $parameters[] = $params[2]; }
	if ( isset( $params[3] ) ) { $parameters[] = $params[3]; }
	if ( isset( $params[4] ) ) { $parameters[] = $params[4]; }
	if ( isset( $params[5] ) ) { $parameters[] = $params[5]; }
	if ( isset( $params[6] ) ) { $parameters[] = $params[6]; }

	if ( validate_string( $folder ) AND validate_string( $subfolder ) AND validate_string( $action ) )
		return array( $folder, $subfolder, $action, $parameters );
	else 
		throw new GeneralException('Illegal access to spliturl!!!');
}

/**
 * validate that the parameter is composed only by letters or numbers
 * the problem with ctype_alnum is that ctype_alnum('') == false
 * so I had to force to return true in case of an empty string
 * In case of a too long string it return false
 * In case of a string long between 1 and 40 characters il applies the function ctype_alnum
 *
 * @param  string   $string_var :: the string to validate
 * @return boolean
 */
function validate_string( $string_var ) {
	if ( strlen( $string_var ) == 0 ) return true;
	if ( strlen( $string_var ) > 40 ) return false;
	if ( ctype_alnum( $string_var ) ) return true;
	return false;
}

// TODO check this function
function html_escape( $var ) {
	if ( is_array( $var ) ) {
		return array_map('html_escape', $var);
	} else {
		return htmlspecialchars($var, ENT_QUOTES, 'UTF-8');
	}
}

/**
 * @param  string  a string we need to clean
 * @param  string  separator to sobstitude to unwanted chars
 * @param  bool    if true the returned string will be lowercase
 *
 * @return string  a clean string
 *
 * Functions that clean string so they can be used in a URL
 */
function url_title($str, $separator = '-', $lowercase = TRUE) {
	
	$q_separator = preg_quote($separator);

	$trans = array(
		'&.+?;'                 => '',
		'[^a-z0-9 _-]'          => '',
		'\s+'                   => $separator,
		'('.$q_separator.')+'   => $separator
	);

	$str = strip_tags($str);

	foreach ($trans as $key => $val) {
		$str = preg_replace("#".$key."#i", $val, $str);
	}

	if ($lowercase === TRUE) {
		$str = strtolower($str);
	}

	return trim($str, $separator);
}

/**
 * @param  string  a string with accents
 *
 * @return string  a clean string
 *
 * Char with accents will be sobstituted from corresponding HTML code
 */
function clear_string( $str ) {
    $count	= 1;
    $out	= '';
    $temp	= array();

    for ($i = 0, $s = strlen($str); $i < $s; $i++) {
 	   $ordinal = ord($str[$i]);

 	   if ($ordinal < 128) {
 			if (count($temp) == 1) {
 				$out  .= '&#'.array_shift($temp).';';
 				$count = 1;
 			}

 			$out .= $str[$i];
 	   } else {
 		   if (count($temp) == 0) {
 			   $count = ($ordinal < 224) ? 2 : 3;
 		   }
	
 		   $temp[] = $ordinal;
	
 		   if (count($temp) == $count) {
 			   $number = ($count == 3) ? (($temp['0'] % 16) * 4096) + (($temp['1'] % 64) * 64) + ($temp['2'] % 64) : (($temp['0'] % 32) * 64) + ($temp['1'] % 64);

 			   $out .= '&#'.$number.';';
 			   $count = 1;
 			   $temp = array();
 		   }
 	   }
    }

    return $out;
}

function truncate_string($string, $length, $dots = '&hellip;') {
    return (strlen($string) > $length) ? substr($string, 0, $length) . $dots : $string;
}
