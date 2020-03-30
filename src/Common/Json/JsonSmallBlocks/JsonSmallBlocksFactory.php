<?php

/**
 * Created Fabio Mattei
 * Date: 2020-03-29
 * Time: 18:21
 */

namespace Fabiom\UglyDuckling\Common\Json\JsonSmallBlocks;

use Fabiom\UglyDuckling\Common\Json\JsonSmallBlocks\DefaultBlocks\ButtonBlock;

/**
 * 
 */
class JsonSmallBlocksFactory {

	protected /* array */ $smallBlocks;

	
	function __construct() {
		$this->smallBlocks = array();
	}

	function loadDefaults() {
        $this->addJsonSmallBlock(new ButtonBlock);
    }

    /**
     * Add a factory to the factories container
     * @param JsonSmallBlock $smallBlock
     */
    public function addJsonSmallBlock( JsonSmallBlock $smallBlock ) {
        if (array_key_exists($smallBlock::BLOCK_TYPE, $this->smallBlocks)) {
            $this->smallBlocks[$smallBlock::BLOCK_TYPE] = $smallBlock;
        }
    }

}
