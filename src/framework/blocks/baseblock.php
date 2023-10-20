<?php

class BaseBlock {
  
  	/**
	 * Overwrite this method with the content you want your block to show 
	 */
    function show() {
        return '';
    }
	
  	/**
	 * Overwrite this method with the content you want to put in your html header
	 * It can be useful if you need to load a css or a javascript file for this block
	 * to work properly.
	 */
    function addToHead() {
        return '';
    }
	
  	/**
	 * Overwrite this method with the content you want to put at the very bottom of your page
	 * It can be useful if you need to load a javascript file for this block
	 */
    function addToFoot() {
        return '';
    }
}