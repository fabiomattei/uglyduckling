<?php

namespace Fabiom\UglyDuckling\Common\Status;

use Fabiom\UglyDuckling\Common\Blocks\BaseHTMLBlock;
use Fabiom\UglyDuckling\Common\HTMLStaticBlocks\HTMLStaticBlockFactory;
use Fabiom\UglyDuckling\Common\Json\JsonLoader;
use Fabiom\UglyDuckling\Common\Json\JsonTemplates\JsonTemplateFactoriesContainer;

class HtmlBlockBuilder {

    /**
     * It takes a resource name ($resourceName), it checks if there is any static HTMLBlock having that name,
     * if it is it returns an HTMLBlock,
     * it there is not it returns a dynamic HTMLBlock built on the base of the json resource file having
     * the name $resourceName
     *
     * @param string $resourceName
     * @param JsonLoader $jsonLoader
     * @param JsonTemplateFactoriesContainer $jsonTemplateFactoriesContainer
     * @param HTMLStaticBlockFactory $htmlStaticBlockFactory
     * @return BaseHTMLBlock
     * @throws \Exception
     */
    public static function getHTMLBlock( string $resourceName, JsonLoader $jsonLoader, JsonTemplateFactoriesContainer $jsonTemplateFactoriesContainer, HTMLStaticBlockFactory $htmlStaticBlockFactory ): BaseHTMLBlock  {
        if ( $htmlStaticBlockFactory->isHTMLBlockSupported( $resourceName ) ) {
            return $htmlStaticBlockFactory->getHTMLBlock( $resourceName );
        } else {
            return $jsonTemplateFactoriesContainer->getHTMLBlock( $jsonLoader->loadResource( $resourceName ) );
        }
    }

    /**
     * It takes a resource name ($resourceName), it checks if there is any static HTMLBlock having that name,
     * if it is it returns an HTMLBlock,
     * it there is not it returns a dynamic HTMLBlock built on the base of the json resource file having
     * the name $resourceName
     *
     * @param string $resourceName
     * @param JsonLoader $jsonLoader
     * @param JsonTemplateFactoriesContainer $jsonTemplateFactoriesContainer
     * @param HTMLStaticBlockFactory $htmlStaticBlockFactory
     * @return BaseHTMLBlock
     * @throws \Exception
     */
    public static function getBlockName( string $resourceName, JsonLoader $jsonLoader, JsonTemplateFactoriesContainer $jsonTemplateFactoriesContainer, HTMLStaticBlockFactory $htmlStaticBlockFactory ): string {
        if ( $htmlStaticBlockFactory->isHTMLBlockSupported( $resourceName ) ) {
            return $htmlStaticBlockFactory->getHTMLBlock( $resourceName )::BLOCK_NAME;
        } else {
            return $jsonLoader->loadResource( $resourceName )->name;
        }
    }

}
