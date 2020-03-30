<?php

/**
 * Created Fabio Mattei
 * Date: 2020-03-29
 * Time: 18:21
 */

/**
 * 
 */
class JsonSmallBlocksFactoryContainer {

	public $factories;
	
	function __construct() {
		$this->factories = array();
	}

	/**
     * Add a factory to the factories container
     * @param $jsonTemplateFactory
     */
    public function addJsonTemplateFactory( $smallBlockFactory ) {
        $this->factories[] = $jsonTemplateFactory;
    }

}
