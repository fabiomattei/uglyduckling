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

	/**
     * Check if a given $word is between the words $start and $end in a $string
     * This is useful in order to check is in a particular query
     * 
     * Ex.
     * $word = "name", $string = "SELECT name, address FROM People;", $start = "SELECT", $end = "FROM"
     * will return true because the word name is in the string between the words START and END
     *
     * @param string $word
     * @param string $string
     * @param string $start
     * @param string $end
     * @return bool
     */
    static function isStringBetween( $word, $string, $start, $end ): bool {
        $string = ' ' . $string;
        $ini = strpos( $string, $start );
        if ($ini == 0) return false;
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        $string_in_the_middle = substr($string, $ini, $len);
        if ( strpos( $string_in_the_middle, $word ) !== false ) {
            return true;
        } else {
            return false;
        }
    }

}

