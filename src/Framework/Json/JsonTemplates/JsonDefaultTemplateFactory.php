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
            echo $jsonStructure->type;
            print_r($jsonSmallPartialTemplates);
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
            $jsonResource = JsonDefaultTemplateFactory::loadResource($resourcesIndex, $resourceName);
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

    /**
     * Load a resource from file specified with array index
     *
     * @param string $resourceName
     * @return mixed, a php structure that mirrors the json structure
     * @throws \Exception
     */
    static public function loadResource( array $index_resources, string $resourceName ) {
        if ( array_key_exists( $resourceName, $index_resources ) ) {
            if ( file_exists( $index_resources[$resourceName] ) ) {
                $handle = fopen($index_resources[$resourceName], 'r');
                return JsonLoader::json_decode_with_error_control(fread($handle, filesize($index_resources[$resourceName])), $index_resources[$resourceName] );
            } else {
                throw new \Exception('[JsonLoader] :: Path associated to resource does not exists!!! Path required: ' . $index_resources[$resourceName]->path);
            }
        } else {
            throw new \Exception('[JsonLoader] :: Resource '.$resourceName.' undefined in array index!!!');
        }
    }

    /**
     * Decode json string with error control
     *
     * based on json_decode, it builds a php structure based on the json structure.
     * throws exceptions
     *
     * @param $data string that contains the json structure
     *
     * @return mixed, a php structure that mirrors the json structure
     *
     * @throws \InvalidArgumentException after the error check
     * JSON_ERROR_DEPTH
     * JSON_ERROR_STATE_MISMATCH
     * JSON_ERROR_CTRL_CHAR
     * JSON_ERROR_SYNTAX
     * JSON_ERROR_UTF8
     *
     */
    static public function json_decode_with_error_control( string $jsondata, string $fileNameAndPath ) {
        $loadeddata = json_decode( $jsondata );
        switch (json_last_error()) {
            case JSON_ERROR_NONE:
                // throw new \Exception(' - No errors');
                break;
            case JSON_ERROR_DEPTH:
                throw new \InvalidArgumentException('[JsonLoader json_decode error] :: Maximum stack depth exceeded ::'. $fileNameAndPath .' '.json_last_error_msg());
                break;
            case JSON_ERROR_STATE_MISMATCH:
                throw new \InvalidArgumentException('[JsonLoader json_decode error] :: Underflow or the modes mismatch ::'. $fileNameAndPath .' '.json_last_error_msg());
                break;
            case JSON_ERROR_CTRL_CHAR:
                throw new \InvalidArgumentException('[JsonLoader json_decode error] :: Unexpected control character found ::'. $fileNameAndPath .' '.json_last_error_msg());
                break;
            case JSON_ERROR_SYNTAX:
                throw new \InvalidArgumentException('[JsonLoader json_decode error] :: Syntax error, malformed JSON ::'. $fileNameAndPath .' '.json_last_error_msg());
                break;
            case JSON_ERROR_UTF8:
                throw new \InvalidArgumentException('[JsonLoader json_decode error] :: Malformed UTF-8 characters, possibly incorrectly encoded ::'. $fileNameAndPath .' '.json_last_error_msg());
                break;
            default:
                throw new \InvalidArgumentException('[JsonLoader json_decode error] :: Unknown error ::'. $fileNameAndPath .' '. json_last_error_msg());
                break;
        }
        return $loadeddata;
    }

}
