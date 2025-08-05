<?php

namespace Fabiom\UglyDuckling\Framework\Utils;

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
     * Check if a given $word is between the words $start and $end in a string $container
     * This is useful in order to check if a field is in a particular query
     *
     * Ex.
     * $word = "name", $string = "SELECT name, address FROM People;", $start = "SELECT", $end = "FROM"
     * will return true because the word name is in the string between the words START and END
     *
     * @param string $field
     * @param string $container
     * @return bool
     */
    static function isFieldInSqlSelect( string $field, string $sql ): bool {
        $sql = ' ' . $sql;
        if ( strpos( $sql, strtolower('RECURSIVE') ) ) {
            $ini = strrpos( $sql, strtolower('SELECT') ); // finding the position of the last occurrence of $start in $sql
        } else {
            $ini = strpos( $sql, strtolower('SELECT') ); // finding the position of the last occurrence of $start in $sql
        }
        if ($ini == 0) return false;
        $ini += strlen('SELECT');
        $len = strpos($sql, strtolower('FROM'), $ini) - $ini;
        $string_in_the_middle = substr($sql, $ini, $len);
        if ( strpos( $string_in_the_middle, $field ) !== false ) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * This is the case unsensitive version of the method isStringBetween
     * Check if a given $word is between the words $start and $end in a string $container
     * This is useful in order to check if a field is in a particular query
     *
     * Ex.
     * $word = "name", $string = "SELECT name, address FROM People;", $start = "SELECT", $end = "FROM"
     * will return true because the word name is in the string between the words START and END
     *
     * @param string $word
     * @param string $container
     * @return bool
     */
    static function isFieldInSqlSelectCaseUnsensitive( string $field, string $container ): bool {
        return StringUtils::isFieldInSqlSelect( strtolower($field), strtolower($container) );
    }

    /**
     * Check if a given $word is between the words $start and $end in a string $container
     * This is useful in order to check if a field is in a particular query
     *
     * Ex.
     * $word = "name", $string = "SELECT name, address FROM People;", $start = "SELECT", $end = "FROM"
     * will return true because the word name is in the string between the words START and END
     *
     * @param string $word
     * @param string $container
     * @param string $start
     * @param string $end
     * @return bool
     */
    static function isStringBetween( string $word, string $container, string $start, string $end ): bool {
        $container = ' ' . $container;
        $ini = strpos( $container, $start ); // finding the position of the last occurrence of $start in $container
        if ($ini == 0) return false;
        $ini += strlen($start);
        $len = strpos($container, $end, $ini) - $ini;
        $string_in_the_middle = substr($container, $ini, $len);
        if ( strpos( $string_in_the_middle, $word ) !== false ) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * This is the case unsensitive version of the method isStringBetween
     * Check if a given $word is between the words $start and $end in a string $container
     * This is useful in order to check if a field is in a particular query
     *
     * Ex.
     * $word = "name", $string = "SELECT name, address FROM People;", $start = "SELECT", $end = "FROM"
     * will return true because the word name is in the string between the words START and END
     *
     * @param string $word
     * @param string $container
     * @param string $start
     * @param string $end
     * @return bool
     */
    static function isStringBetweenCaseUnsensitive( string $word, string $container, string $start, string $end ): bool {
        return StringUtils::isStringBetween( strtolower($word), strtolower($container), strtolower($start), strtolower($end) );
    }

    public static function generateRandomString($length = 8, $possible = "0123456789abcdfghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ") {
        $password = "";

        $i = 0;
        while ($i < $length) {
            $char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
            if (!strstr($password, $char)) {
                $password .= $char;
                $i++;
            }
        }
        return $password;
    }

    /**
     * If $string is longer than $lenght characters it truncate the string and adds $suspensionPoints
     *
     * @param string $string
     * @param int $lenght
     * @param string $suspensionPoints default '...'
     * @return string
     */
    public static function stringMaxLenght(string $string, int $lenght, string $suspensionPoints = '...') {
        return (strlen($string) > 13) ? substr($string,0,$lenght).$suspensionPoints : $string;
    }

    /**
     * @param $file_name
     * @return string
     *
     * This function cleans a file name in order to avoid funny characters
     *
     */
    public static function cleanFileName($file_name){
        $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
        $file_name_str = pathinfo($file_name, PATHINFO_FILENAME);

        // Replaces all spaces with hyphens.
        $file_name_str = str_replace(' ', '-', $file_name_str);
        // Removes special chars.
        $file_name_str = preg_replace('/[^A-Za-z0-9\-\_]/', '', $file_name_str);
        // Replaces multiple hyphens with single one.
        $file_name_str = preg_replace('/-+/', '-', $file_name_str);

        $clean_file_name = $file_name_str.'.'.$file_ext;

        return $clean_file_name;
    }

}