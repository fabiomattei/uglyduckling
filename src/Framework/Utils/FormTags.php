<?php

namespace Fabiom\UglyDuckling\Framework\Utils;

// fuctions that symplifies the selected property in a form
function selected( $variable, $term ) {
	if ( $variable == $term ) return 'selected="selected"';
	return '';
}

// fuctions that symplifies the selected property in a form
function checked_if_contains( $variable, $term ) {
	if ( strpos( $variable, $term ) !== false ) {
	    return 'checked="checked"';
	}
	return '';
}

// fuctions that symplifies the selected property in a form
function jsselected( $variable, $term ) {
	if ( $variable == $term ) return 'selected=\"selected\"';
	return '';
}

// fuctions that symplifies the selected property in a form
function checked( $variable, $term ) {
	if ( $variable == $term ) return 'checked="checked"';
	return '';
}

// fuctions that symplifies the selected property in a form
function jschecked( $variable, $term ) {
	if ( $variable == $term ) return 'checked=\"checked\"';
	return '';
}
