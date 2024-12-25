<?php

/**
 * Created by Fabio Mattei
 * Date: 19/09/2019
 * Time: 08:16
 */

namespace Fabiom\UglyDuckling\Framework\Json\JsonTemplates;

use Fabiom\UglyDuckling\Framework\Blocks\CardHTMLBlock;
use Fabiom\UglyDuckling\Framework\Json\JsonLoader;
use Fabiom\UglyDuckling\Framework\Json\JsonTemplates\Chartjs\ChartjsJsonTemplate;
use Fabiom\UglyDuckling\Framework\Json\JsonTemplates\Form\FormJsonTemplate;
use Fabiom\UglyDuckling\Framework\Json\JsonTemplates\Grid\GridJsonTemplate;
use Fabiom\UglyDuckling\Framework\Json\JsonTemplates\Info\InfoJsonTemplate;
use Fabiom\UglyDuckling\Framework\Json\JsonTemplates\Table\TableJsonTemplate;
use Fabiom\UglyDuckling\Framework\Json\JsonTemplates\Dashboard\DashboardJsonTemplate;
use Fabiom\UglyDuckling\Framework\Json\JsonTemplates\Uniform\UniformJsonTemplate;

class JsonDefaultTemplateFactory extends JsonTemplateFactory {

    private /* TableJsonTemplate */ $tableBuilder;
    private /* ChartjsJsonTemplate */ $chartjsBuilder;
    private /* InfoJsonTemplate */ $infoBuilder;
    private /* FormJsonTemplate */ $formBuilder;
    private /* DashboardJsonTemplate */ $dashboardJsonTemplate;
    private /* UniformJsonTemplate */ $uniformJsonTemplate;
    private /* GridJsonTemplate */ $gridJsonTemplate;

    private array $resourcesIndex;
    private array $tagsIndex;
    private array $jsonResourceTemplates;
    private array $jsonTabTemplates;

    /**
     * PanelBuilder constructor.
     * @param $tableBuilder
     */
    private static function getHTMLBlock( $resourcesIndex, $tagsIndex, $jsonResourceTemplates, $jsonTabTemplates, $pageStatus, $resourceName ) {
        $this->resourcesIndex = $resourcesIndex;
        $this->tagsIndex = $tagsIndex;
        $this->jsonResourceTemplates = $jsonResourceTemplates;
        $this->jsonTabTemplates = $jsonTabTemplates;



        if ( array_key_exists( $resourceName, $resourcesIndex ) ) {
            $jsonResource = JsonDefaultTemplateFactory::loadResource($resourcesIndex, $resourceName);


        }


        $this->tableBuilder = new TableJsonTemplate( $pageStatus );
        $this->chartjsBuilder = new ChartjsJsonTemplate( $pageStatus );
        $this->infoBuilder = new InfoJsonTemplate( $pageStatus );
        $this->formBuilder = new FormJsonTemplate( $pageStatus );
        $this->dashboardJsonTemplate = new DashboardJsonTemplate( $pageStatus );
        $this->uniformJsonTemplate = new UniformJsonTemplate( $pageStatus );
        $this->gridJsonTemplate = new GridJsonTemplate( $pageStatus );
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
                throw new \Exception('[JsonLoader] :: Path associated to resource does not exists!!! Path required: ' . $this->resourcesIndex[$resourceName]->path);
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


    public function isResourceSupported( $resource ) {
        return in_array($resource->metadata->type, array(
            DashboardJsonTemplate::blocktype,
            UniformJsonTemplate::blocktype,
            TableJsonTemplate::blocktype,
            ChartjsJsonTemplate::blocktype,
            InfoJsonTemplate::blocktype,
            FormJsonTemplate::blocktype,
            GridJsonTemplate::blocktype
        ));
    }

    /**
     * Return an HTML Block
     *
     * The HTML block type depends from the resource->metadata->type field in the json strcture
     *
     * @param $resource json structure
     * @param CardHTMLBlock $panelBlock
     */
    public function getHTMLBlock( $resource ) {
        if ( $resource->metadata->type == DashboardJsonTemplate::blocktype ) {
            $this->dashboardJsonTemplate->setResource($resource);
            return $this->dashboardJsonTemplate->createHTMLBlock();
        }

        if ( $resource->metadata->type == GridJsonTemplate::blocktype ) {
            $this->gridJsonTemplate->setResource($resource);
            return $this->gridJsonTemplate->createHTMLBlock();
        }

        if ( $resource->metadata->type == UniformJsonTemplate::blocktype ) {
            $this->uniformJsonTemplate->setResource($resource);
            return $this->uniformJsonTemplate->createHTMLBlock();
        }

        if ( $resource->metadata->type == TableJsonTemplate::blocktype ) {
            $this->tableBuilder->setResource($resource);
            return $this->tableBuilder->createTable();
        }

        if ( $resource->metadata->type == ChartjsJsonTemplate::blocktype ) {
            $this->chartjsBuilder->setResource($resource);
            return $this->chartjsBuilder->createChart();
        }

        if ( $resource->metadata->type == InfoJsonTemplate::blocktype ) {
            $this->infoBuilder->setResource($resource);
            return $this->infoBuilder->createInfo();
        }

        if ( $resource->metadata->type == FormJsonTemplate::blocktype ) {
            $this->formBuilder->setResource($resource);
            return $this->formBuilder->createForm();
        }

    }

}
