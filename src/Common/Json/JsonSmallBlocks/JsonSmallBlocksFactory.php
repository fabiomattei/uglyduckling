<?php

/**
 * Created Fabio Mattei
 * Date: 2020-03-29
 * Time: 18:21
 */

/**
 * 
 */
class JsonSmallBlocksFactory {

	protected $resource;
    protected /* JsonTemplateFactoriesContainer */ $jsonTemplateFactoriesContainer;
	
	function __construct() {
		
	}

    /**
     * @param $container JsonSmallBlocksFactoryContainer
     */
    public function setJsonSmallBlocksFactoryContainer( JsonSmallBlocksFactoryContainer $container) {
        $this->jsonTemplateFactoriesContainer = $container;
    }

    

}

