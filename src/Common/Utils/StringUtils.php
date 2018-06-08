<?php


namespace Firststep\Common\Utils;

/**
* 
*/
class StringUtils {


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
	static function validate_string( string $string_var ) : bool {
		if ( strlen( $string_var ) == 0 ) return true;
		if ( strlen( $string_var ) > 40 ) return false;
		if ( ctype_alnum( $string_var ) ) return true;
		return false;
	}

}

