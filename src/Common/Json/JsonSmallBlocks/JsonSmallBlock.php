<?php

/**
 * Created Fabio Mattei
 * Date: 2020-03-29
 * Time: 18:21
 */

namespace Fabiom\UglyDuckling\Common\Json\JsonSmallBlocks;

/**
 * A Json small block is a JSON resource (object or array or composite)
 * that we need to convert in HTML
 * It is usually not a block that lives by itsef it is a block that is part
 * of a larger resource.
 *
 * A Json resource can contain one or more jsonSmallBlocks
 */
class JsonSmallBlock {

    const BLOCK_TYPE = 'smallblock';
	
	function __construct() {
		
	}

	/**
	 * Takes a JSON resource (object or array or composite) and convert it in HTML
	 */
	function getHTML( $resource, $parameters ): string {
		return 'Undefined JsonSmallBlock';
	}

}
