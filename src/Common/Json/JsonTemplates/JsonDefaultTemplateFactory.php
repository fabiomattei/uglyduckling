<?php

/**
 * Created by Fabio Mattei
 * Date: 19/09/2019
 * Time: 08:16
 */

namespace Fabiom\UglyDuckling\Common\Json\JsonTemplates;

use Fabiom\UglyDuckling\Common\Blocks\CardHTMLBlock;
use Fabiom\UglyDuckling\Common\Json\JsonTemplates\Chartjs\ChartjsJsonTemplate;
use Fabiom\UglyDuckling\Common\Json\JsonTemplates\Form\FormJsonTemplate;
use Fabiom\UglyDuckling\Common\Json\JsonTemplates\Info\InfoJsonTemplate;
use Fabiom\UglyDuckling\Common\Json\JsonTemplates\Table\TableJsonTemplate;
use Fabiom\UglyDuckling\Common\Json\JsonTemplates\Dashboard\DashboardJsonTemplate;
use Fabiom\UglyDuckling\Common\Json\JsonTemplates\Uniform\UniformJsonTemplate;
use Fabiom\UglyDuckling\Common\Router\ResourceRouter;

class JsonDefaultTemplateFactory extends JsonTemplateFactory {

    private /* TableJsonTemplate */ $tableBuilder;
    private /* ChartjsJsonTemplate */ $chartjsBuilder;
    private /* InfoJsonTemplate */ $infoBuilder;
    private /* FormJsonTemplate */ $formBuilder;
    private /* DashboardJsonTemplate */ $dashboardJsonTemplate;
    private /* UniformJsonTemplate */ $uniformJsonTemplate;

    /**
     * PanelBuilder constructor.
     * @param $tableBuilder
     */
    public function __construct( $applicationBuilder, $pageStatus ) {
        $this->tableBuilder = new TableJsonTemplate( $applicationBuilder, $pageStatus );
        $this->chartjsBuilder = new ChartjsJsonTemplate( $applicationBuilder, $pageStatus );
        $this->infoBuilder = new InfoJsonTemplate( $applicationBuilder, $pageStatus );
        $this->formBuilder = new FormJsonTemplate( $applicationBuilder, $pageStatus );
        $this->dashboardJsonTemplate = new DashboardJsonTemplate( $applicationBuilder, $pageStatus );
        $this->uniformJsonTemplate = new UniformJsonTemplate( $applicationBuilder, $pageStatus );

        $this->action = '';
    }

    public function isResourceSupported( $resource ) {
        return in_array($resource->metadata->type, array(
            DashboardJsonTemplate::blocktype,
            UniformJsonTemplate::blocktype,
            TableJsonTemplate::blocktype,
            ChartjsJsonTemplate::blocktype,
            InfoJsonTemplate::blocktype,
            FormJsonTemplate::blocktype
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
