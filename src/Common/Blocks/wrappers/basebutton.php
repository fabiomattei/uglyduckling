<?php

namespace templates\blocks\wrappers;

use core\blocks\BaseBlock;

class BaseButton extends BaseBlock {

	/**
	 * Receive in array with the following structure and create all necessary buttons
	 *
	 * array ( array( 'nextstep' => 1, 'label' => 'Crea una richiesta di spesa', 'form' => 'expenserequestv1') )
	 */
	function __construct( $triggers, $chapter_slug, $process_slug ) {
		$this->triggers = $triggers;
		$this->chapter_slug = $chapter_slug;
		$this->process_slug = $process_slug;
	}
	
    function show() {
		$out = '';
		foreach ($this->triggers as $trigger) {
			$out .= make_link( $trigger['label'], 'paperwork', 'processnew', 
				array( 'parameters' => $this->chapter_slug.'/'.$this->process_slug.'/'.$trigger['nextstep'].'/'.$trigger['form'], ) ).' ';
		}
		return $out;
    }

}
