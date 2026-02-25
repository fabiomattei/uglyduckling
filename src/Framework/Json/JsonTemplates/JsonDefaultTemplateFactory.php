<?php

/**
 * Created by Fabio Mattei
 * Date: 19/09/2019
 * Time: 08:16
 */

namespace Fabiom\UglyDuckling\Framework\Json\JsonTemplates;

use Fabiom\UglyDuckling\Framework\Json\JsonLoader;
use Fabiom\UglyDuckling\Framework\Utils\PageStatus;

class JsonDefaultTemplateFactory {

    /**
     * @param $jsonStructure
     * @param PageStatus $pageStatus
     * @param $jsonTagTemplates: index listing all defined json tags, array list having the tag template name as key and the tag template file path as value
     * @return string
     */
    public static function getHTMLTag( $jsonStructure, PageStatus $pageStatus, array $jsonTagTemplates ): string {
        if ( isset($jsonStructure->type) ) {
            if ( array_key_exists( $jsonStructure->type, $jsonTagTemplates) ){
                $tag = new $jsonTagTemplates[$jsonStructure->type]( $jsonStructure, $pageStatus, $jsonTagTemplates );
                return $tag->getHTML();
            }
        }
        return 'undefined tag';
    }

    /**
     * @param $jsonStructure
     * @param PageStatus $pageStatus
     * @param $jsonTagTemplates: index listing all defined json tags, array list having the tag template name as key and the tag template file path as value
     * @return string
     */
    public static function getHTMLSmallPartial( $jsonStructure, $mainJsonStructure, PageStatus $pageStatus, array $jsonSmallPartialTemplates, array $jsonTagTemplates ): string {
        if ( isset($jsonStructure->type) ) {
            if ( array_key_exists( $jsonStructure->type, $jsonSmallPartialTemplates) ){
                $smallPartial = new $jsonSmallPartialTemplates[$jsonStructure->type]( $jsonStructure, $mainJsonStructure, $pageStatus, $jsonSmallPartialTemplates, $jsonTagTemplates );
                return $smallPartial->getHTML();
            }
        }
        return 'undefined tag';
    }

    /**
     * PanelBuilder constructor.
     * @param $tableBuilder
     */
    public static function getHTMLBlock($resourcesIndex, $jsonResourceTemplates, $jsonTagTemplates, $pageStatus, $resourceName ) {
        if ( array_key_exists( $resourceName, $resourcesIndex ) ) {
            $jsonResource = JsonLoader::loadResource($resourcesIndex, $resourceName);
            if ( array_key_exists( $jsonResource->metadata->type, $jsonResourceTemplates ) ) {
                if ($jsonResource->metadata->type == 'grid') {
                    $respourceJsonTemplate = new $jsonResourceTemplates[$jsonResource->metadata->type]( $jsonResource, $pageStatus, $resourcesIndex, $jsonResourceTemplates, $jsonTagTemplates );
                    return $respourceJsonTemplate->createHTMLBlock();
                } else {
                    $respourceJsonTemplate = new $jsonResourceTemplates[$jsonResource->metadata->type]( $jsonResource, $pageStatus, $resourcesIndex, $jsonResourceTemplates, $jsonTagTemplates );
                    return '<div class="row"><div class="col-12">'.$respourceJsonTemplate->createHTMLBlock().'</div></div>';
                }
            }
        }
    }

    /**
     * Return an instantiated object of a use case
     *
     * @param $useCasesIndex: index listing all defined usecases, array list having the use case name as key and the use case file path as value
     * @param $resourceName
     * @param $pageStatus: object containig all the status of the page we are composing with a URL call
     * @return mixed|void
     */
    public static function getUseCase( array $useCasesIndex, $jsonUseCase, PageStatus $pageStatus ) {
        if ( array_key_exists( $jsonUseCase->name, $useCasesIndex ) ) {
            return new $useCasesIndex[$jsonUseCase->name]($jsonUseCase, $pageStatus);
        }
    }

}
