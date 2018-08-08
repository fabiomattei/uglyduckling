<?php

namespace Firststep\Common\Blocks;

use Firststep\Common\Blocks\BaseBlock;

class ColWidth extends BaseBlock {
	
	const EXTRA_SMALL = 'col-';         // < 576px
	const SMALL       = 'col-sm-';      // >= 576px
	const MEDIUM      = 'col-md-';      // >= 768px
	const LARGE       = 'col-lg-';      // >= 992px
	const EXTRA_LARGE = 'col-xl-';      // >= 1200px
	
	public function get( $type, $width ) {
		return $type.$width;
	}
	
}
