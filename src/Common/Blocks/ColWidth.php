<?php

namespace Fabiom\UglyDuckling\Common\Blocks;

use Fabiom\UglyDuckling\Common\Blocks\BaseHTMLBlock;

class ColWidth extends BaseHTMLBlock {
	
	const EXTRA_SMALL = 'col-';         // < 576px
	const SMALL       = 'col-sm-';      // >= 576px
	const MEDIUM      = 'col-md-';      // >= 768px
	const LARGE       = 'col-lg-';      // >= 992px
	const EXTRA_LARGE = 'col-xl-';      // >= 1200px
	
	public function get( $type, $width ) {
		return $type.$width.'';
	}

	public static function getWidth($type, $width ) {
        return $type.$width.'';
    }
	
}
