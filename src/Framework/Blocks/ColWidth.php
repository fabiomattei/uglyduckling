<?php

namespace Fabiom\UglyDuckling\Framework\Blocks;

use Fabiom\UglyDuckling\Framework\Blocks\BaseHTMLBlock;

class ColWidth extends BaseHTMLBlock {
	
	const EXTRA_SMALL = 'col-';         // < 576px
	const SMALL       = 'col-sm-';      // >= 576px
	const MEDIUM      = 'col-md-';      // >= 768px
	const LARGE       = 'col-lg-';      // >= 992px
	const EXTRA_LARGE = 'col-xl-';      // >= 1200px
	
	public function get( $type, $width ) {
	    if ( !isset( $width ) OR $width == '' ) {
	        return '';
        } else {
            return $type.$width.'';
        }
	}

	public static function getWidth($type, $width ) {
        if ( !isset( $width ) OR $width == '' ) {
            return '';
        } else {
            return $type.$width.'';
        }
    }
	
}
