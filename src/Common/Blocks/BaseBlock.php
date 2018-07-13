<?php

namespace Firststep\Common\Blocks;

abstract class BaseBlock {
  
    /**
	 * Overwrite this method with the content you want your block to show 
	 */
    function show(): string {
        return '';
    }
	
  /**
	 * Overwrite this method with the content you want to put in your html header
	 * It can be useful if you need to load a css or a javascript file for this block
	 * to work properly.
	 */
    function addToHead(): string {
        return '';
    }
	
  /**
	 * Overwrite this method with the content you want to put at the very bottom of your page
	 * It can be useful if you need to load a javascript file for this block
	 */
    function addToFoot(): string {
        return '';
    }

  /**
   * Overwrite this method with the content you want to put in your html header
   * It can be useful if you need to write some css or javascript code
   */
    function subAddToHead(): string {
        return '';
    }
  
  /**
   * Overwrite this method with the content you want to put at the very bottom of your page
   * It can be useful if you need to write some css or javascript code
   */
    function subAddToFoot(): string {
        return '';
    }
}
