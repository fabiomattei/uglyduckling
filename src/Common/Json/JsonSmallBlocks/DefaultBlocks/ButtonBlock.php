<?php

/**
 * Created Fabio Mattei
 * Date: 2020-03-30
 * Time: 19:21
 */

namespace Fabiom\UglyDuckling\Common\Json\JsonSmallBlocks\DefaultBlocks;

use Fabiom\UglyDuckling\Common\Json\JsonSmallBlocks\JsonSmallBlock;

/**
 * A Json small block is a JSON resource (object or array or composite)
 * that we need to convert in HTML
 */
class ButtonBlock extends JsonSmallBlock {

    const BLOCK_TYPE = 'smallblock';

	function __construct() {

	}

	/**
	 * Takes a JSON resource (object or array or composite) and convert it in HTML
	 */
	function getHTML( $resource, $parameters ): string {
        return '';
	}

}
