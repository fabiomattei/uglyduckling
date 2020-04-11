<?php

/**
 * Created by Fabio Mattei
 * Date: 19/09/2019
 * Time: 08:16
 */

namespace Fabiom\UglyDuckling\Common\Json\JsonTemplates;

use Fabiom\UglyDuckling\Common\Blocks\CardHTMLBlock;
use Fabiom\UglyDuckling\Common\Json\JsonTemplates\Chartjs\ChartjsJsonTemplate;
use Fabiom\UglyDuckling\Common\Json\JsonTemplates\Export\ExportJsonTemplate;
use Fabiom\UglyDuckling\Common\Json\JsonTemplates\Search\SearchJsonTemplate;
use Fabiom\UglyDuckling\Common\Json\JsonTemplates\Form\FormJsonTemplate;
use Fabiom\UglyDuckling\Common\Json\JsonTemplates\Info\InfoJsonTemplate;
use Fabiom\UglyDuckling\Common\Json\JsonTemplates\Table\TableJsonTemplate;
use Fabiom\UglyDuckling\Common\Json\JsonTemplates\Dashboard\DashboardJsonTemplate;
use Fabiom\UglyDuckling\Common\Router\Router;

class JsonDefaultTemplateFactory extends JsonTemplate {

    private /* TableJsonTemplate */ $tableBuilder;
    private /* ChartjsJsonTemplate */ $chartjsBuilder;
    private /* InfoJsonTemplate */ $infoBuilder;
    private /* FormJsonTemplate */ $formBuilder;
    private /* SearchJsonTemplate */ $searchJsonTemplate;
    private /* ExportJsonTemplate */ $exportJsonTemplate;
    private /* DashboardJsonTemplate */ $dashboardJsonTemplate;

    /**
     * PanelBuilder constructor.
     * @param $tableBuilder
     */
    public function __construct( $jsonTemplateFactoriesContainer ) {
        $this->tableBuilder = new TableJsonTemplate;
        $this->tableBuilder->setJsonTemplateFactoriesContainer($jsonTemplateFactoriesContainer);
        $this->chartjsBuilder = new ChartjsJsonTemplate;
        $this->chartjsBuilder->setJsonTemplateFactoriesContainer($jsonTemplateFactoriesContainer);
        $this->infoBuilder = new InfoJsonTemplate;
        $this->infoBuilder->setJsonTemplateFactoriesContainer($jsonTemplateFactoriesContainer);
        $this->formBuilder = new FormJsonTemplate;
        $this->formBuilder->setJsonTemplateFactoriesContainer($jsonTemplateFactoriesContainer);
        $this->searchJsonTemplate = new SearchJsonTemplate;
        $this->searchJsonTemplate->setJsonTemplateFactoriesContainer($jsonTemplateFactoriesContainer);
        $this->exportJsonTemplate = new ExportJsonTemplate;
        $this->exportJsonTemplate->setJsonTemplateFactoriesContainer($jsonTemplateFactoriesContainer);
        $this->dashboardJsonTemplate = new DashboardJsonTemplate;
        $this->dashboardJsonTemplate->setJsonTemplateFactoriesContainer($jsonTemplateFactoriesContainer);
        $this->action = '';
    }

    /**
     * @deprecated
     * @param $panel
     * @return CardHTMLBlock
     * @throws \Exception
     */
    function getPanel($panel) {
        $panelBlock = new CardHTMLBlock;
        $panelBlock->setTitle($panel->title ?? '');
        $panelBlock->setWidth($panel->width ?? '3');
        $panelBlock->setHtmlTemplateLoader( $this->htmlTemplateLoader );

        $resource = $this->jsonloader->loadResource( $panel->resource );

        $panelBlock->setInternalBlockName( $resource->name ?? '' );
        $panelBlock->setBlock($this->getHTMLBlock($resource));

        return $panelBlock;
    }

    /**
     * @deprecated
     * Return a panel containing an HTML Block built with data in the resource field
     *
     * The HTML block type depends from the resource->metadata->type field in the json strcture
     *
     * @param $resource
     * @return CardHTMLBlock
     */
    function getWidePanel( $resource ) {
        $panelBlock = new CardHTMLBlock;
        $panelBlock->setTitle('');
        $panelBlock->setWidth( '12');
        $panelBlock->setHtmlTemplateLoader( $this->htmlTemplateLoader );
        $panelBlock->setInternalBlockName( $resource->name ?? '' );
        $panelBlock->setBlock($this->getHTMLBlock($resource));
        return $panelBlock;
    }

    public function isResourceSupported( $resource ) {
        return in_array($resource->metadata->type, array(
            DashboardJsonTemplate::blocktype, 
            TableJsonTemplate::blocktype,
            ChartjsJsonTemplate::blocktype,
            InfoJsonTemplate::blocktype,
            FormJsonTemplate::blocktype,
            'search', 
            'export'
        ));
    }

    /**
     * Return an HTML Block
     *
     * The HTML block type depends from the resource->metadata->type field in the json strcture
     *
     * @param $resource json strcture
     * @param CardHTMLBlock $panelBlock
     */
    public function getHTMLBlock( $resource ) {
        if ( $resource->metadata->type == DashboardJsonTemplate::blocktype ) {
            $this->dashboardJsonTemplate->setResource($resource);
            return $this->dashboardJsonTemplate->createHTMLBlock();
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
            $this->formBuilder->setAction($this->action . '&postres=' . $resource->name);
            return $this->formBuilder->createForm();
        }

        if ($resource->metadata->type == SearchJsonTemplate::blocktype ) {
            $this->searchJsonTemplate->setResource($resource);
            $this->searchJsonTemplate->setAction($this->routerContainer->makeRelativeUrl(Router::ROUTE_OFFICE_ENTITY_SEARCH, 'res=' . $resource->name));
            return $this->searchJsonTemplate->createHTMLBlock();
        }

        if ($resource->metadata->type == ExportJsonTemplate::blocktype ) {
            $this->exportJsonTemplate->setResource($resource);
            $this->exportJsonTemplate->setAction($this->routerContainer->makeRelativeUrl(Router::ROUTE_OFFICE_ENTITY_EXPORT, 'res=' . $resource->name));
            return $this->exportJsonTemplate->createHTMLBlock();
        }
    }

}
