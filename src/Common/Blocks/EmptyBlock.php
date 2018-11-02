<?php

namespace Firststep\Common\Blocks;

use Firststep\Common\Blocks\BaseBlock;

class EmptyBlock extends BaseBlock {
	
	private $html;
	private $addToHead;
	private $addToFoot;
	private $subAddToHead;
	private $subAddToFoot;

    /**
     * EmptyBlock constructor.
     */
    public function __construct() {
        $this->html = '';
        $this->addToHead = '';
        $this->addToFoot = '';
        $this->subAddToHead = '';
        $this->subAddToFoot = '';
    }

    public function setHtml( $html='' ) {
		$this->html = $html;
	}
	
	public function setAddToHead( $addToHead='' ) {
		$this->addToHead = $addToHead;
	}
	
	public function setAddToFoot( $addToFoot='' ) {
		$this->addToFoot = $addToFoot;
	}
	
	public function setSubAddToHead( $subAddToHead='' ) {
		$this->subAddToHead = $subAddToHead;
	}
	
	public function setSubAddToFoot( $subAddToFoot='' ) {
		$this->subAddToFoot = $subAddToFoot;
	}
  
    /**
	 * Overwrite this method with the content you want your block to show 
	 */
    function show(): string {
        return $this->html;
    }
	
  /**
	 * Overwrite this method with the content you want to put in your html header
	 * It can be useful if you need to load a css or a javascript file for this block
	 * to work properly.
	 */
    function addToHead(): string {
        return $this->addToHead;
    }
	
  /**
	 * Overwrite this method with the content you want to put at the very bottom of your page
	 * It can be useful if you need to load a javascript file for this block
	 */
    function addToFoot(): string {
        return $this->addToFoot;
    }

  /**
   * Overwrite this method with the content you want to put in your html header
   * It can be useful if you need to write some css or javascript code
   */
    function subAddToHead(): string {
        return $this->subAddToHead;
    }
  
  /**
   * Overwrite this method with the content you want to put at the very bottom of your page
   * It can be useful if you need to write some css or javascript code
   */
    function subAddToFoot(): string {
        return $this->subAddToFoot;
    }

}
