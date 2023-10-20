<?php

/* ==============================================================
 * This file contains a function for generating ramdom hashcodes
 * this file it in not loaded by default, if you want to use this 
 * functions you need to load it using the line:
 *
 * utils( 'hashcode' );
 * ============================================================== */
	
/**
 * Generates an hashode that can be used when generating an entity
 * Default given lenght is 76 chars
 *
 * @param        integer    lenght of the resulting string
 *
 * @return       string     random string
 */
function generate_hashcode( $length = 76 ) {
    $password = "";
    $possible = "0123456789abcdfghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";

    $i = 0;
	$possible_lenght_minus_1 = strlen($possible) - 1; 
    while ($i < $length) {
        $char = substr($possible, mt_rand(0, $possible_lenght_minus_1 ), 1);
        $password .= $char;
        $i++;
    }
    return $password;
}
