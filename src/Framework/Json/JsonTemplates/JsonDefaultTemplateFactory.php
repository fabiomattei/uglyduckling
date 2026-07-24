<?php

/**
 * Created by Fabio Mattei
 * Date: 19/09/2019
 * Time: 08:16
 */

namespace Fabiom\UglyDuckling\Framework\Json\JsonTemplates;

use Fabiom\UglyDuckling\Framework\Json\JsonLoader;
use Fabiom\UglyDuckling\Framework\Routing\RouteTable;
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
                if ( !self::isTagTargetVisible($jsonStructure) ) {
                    return '';
                }
                $tag = new $jsonTagTemplates[$jsonStructure->type]( $jsonStructure, $pageStatus, $jsonTagTemplates );
                return $tag->getHTML();
            }
        }
        return 'undefined tag';
    }

    /**
     * A button/link tag points at a route via "resource"/"controller" - either directly on
     * its own JSON (buttontable, buttonsubtable, buttonform, link) or, for AJAX-style tags,
     * nested under "dataudurl" (button, ajaxbutton, buttondiv). A tag with neither isn't a
     * navigable link at all (e.g. tagexample), so it's left visible.
     */
    private static function isTagTargetVisible($jsonStructure): bool {
        $target = self::tagTarget($jsonStructure);
        if ($target === null && isset($jsonStructure->dataudurl)) {
            $target = self::tagTarget($jsonStructure->dataudurl);
        }
        if ($target === null) {
            return true;
        }
        return RouteTable::isVisible($target);
    }

    /**
     * Mirrors UrlServices::make_resource_url()'s own precedence for picking the slug a tag
     * actually navigates to, so visibility is checked against the same target the link
     * would really point at:
     *  - an explicit "url" is a raw literal, not a routed slug - nothing to check
     *  - "resource"+"controller":"partial" together mean "load this resource", so the
     *    resource is the real target and "partial" is just a dispatch-mode marker
     *  - otherwise "controller" wins over "resource" when both are present (the resource is
     *    merely loaded within that controller's page via a "res=" parameter)
     */
    private static function tagTarget($jsonStructure) {
        if (isset($jsonStructure->url)) {
            return null;
        }
        if (isset($jsonStructure->controller) && $jsonStructure->controller === 'partial' && isset($jsonStructure->resource)) {
            return $jsonStructure->resource;
        }
        if (isset($jsonStructure->controller)) {
            return $jsonStructure->controller;
        }
        if (isset($jsonStructure->resource)) {
            return $jsonStructure->resource;
        }
        return null;
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
                $respourceJsonTemplate = new $jsonResourceTemplates[$jsonResource->metadata->type]( $jsonResource, $pageStatus, $resourcesIndex, $jsonResourceTemplates, $jsonTagTemplates );
                return $respourceJsonTemplate->createHTMLBlock();
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
