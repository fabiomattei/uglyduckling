<?php

use Fabiom\UglyDuckling\Common\Blocks\BaseHTMLDashboard;
use Fabiom\UglyDuckling\Common\Blocks\CardHTMLBlock;

$GLOBALS['myTemplateFunctions'] = [];

$GLOBALS['myTemplateFunctions']['dashboard'] = function ( $jsonResource, $jsonLoader, $breadcrumb, $level ) {
    $doc = new BaseHtmlDoc;
    $docs = [$doc];
    if ( isset($jsonResource->description) and is_string($jsonResource->description) ) {
        $doc->paragraph($jsonResource->description);
    }
    if ( isset($jsonResource->docs) and is_array($jsonResource->docs) ) {
        foreach ( $jsonResource->docs as $paragraph) {
            $doc->paragraph($paragraph);
        }
    }
    if (count($jsonResource->panels) > 1) {
        // $doc->paragraph('This page is composed by '. count($jsonResource->panels) . ' sections ');
    }
    if (isset($jsonResource->docimg) and is_string($jsonResource->docimg) ) {
        $doc->img($jsonResource->docimg, ['width'=>'700']);
    }
    if (isset($jsonResource->docimgs) and is_array($jsonResource->docimgs) ) {
        foreach ( $jsonResource->docimgs as $imgs) {
            $doc->img($imgs, ['width'=>'700']);
        }
    }
    if ( !isset($jsonResource->nopanels) ) {
        $chapterNumber = 1;
        foreach ($jsonResource->panels as $panel) {
            if ( $jsonLoader->isJsonResourceIndexedAndFileExists($panel->resource) ) {
                $panelResource = $jsonLoader->loadResource($panel->resource);
                // echo( gettype($panelResource->metadata->type));
                // echo($panelResource->metadata->type."<br>");
                $items = $GLOBALS['myDocFunctions'][$panelResource->metadata->type]($panelResource, $jsonLoader, $breadcrumb . '.' . $chapterNumber, $level + 1);
                if (is_array($items)) {
                    $docs = array_merge($docs, $items);
                } else {
                    $docs[] = $items;
                }
            } else {
                echo $panel->resource." does not exist<br>";
            }
            $chapterNumber += 1;
        }
    }
    if (isset($jsonResource->docprocess) and is_array($jsonResource->docprocess)) {
        foreach ($jsonResource->docprocess as $processResourceName) {
            if ( $jsonLoader->isJsonResourceIndexedAndFileExists($processResourceName) ) {
                $panelResource = $jsonLoader->loadResource($processResourceName);
                $items = $GLOBALS['myDocFunctions'][$panelResource->metadata->type]($panelResource, $jsonLoader, $breadcrumb . '.' . $chapterNumber, $level + 1);
                if (is_array($items)) {
                    $docs = array_merge($docs, $items);
                } else {
                    $docs[] = $items;
                }
            } else {
                echo $panel->resource." does not exist<br>";
            }
            $chapterNumber += 1;
        }
    }
    return $docs;
};

$GLOBALS['myTemplateFunctions']['dashboard'] = function ( $jsonResource, $jsonLoader ): BaseHTMLDashboard {
    $htmlTemplateLoader = $this->applicationBuilder->getHtmlTemplateLoader();

    // this first section of the code run trough all defined panels for the specific
    // dashboard and add each of them to the array $panelRows
    // I am separating panels by row
    $panelRows = array();

    foreach ($jsonResource->panels as $panel) {
        // if there is not array of panels defined for that specific row I am going to create one
        if( !array_key_exists($panel->row, $panelRows) ) $panelRows[$panel->row] = array();
        // adding the panel section, taken from the dashboard json file, to array
        $panelRows[$panel->row][] = $panel;
    }

    $htmlDashboard = new BaseHTMLDashboard;
    $htmlDashboard->setHtmlTemplateLoader( $htmlTemplateLoader );

    foreach ($panelRows as $row) {
        $htmlDashboard->createNewRow();
        foreach ($row as $panel) {
            $panelBlock = new CardHTMLBlock;

            $panelBlock->setTitle($panel->title ?? '');
            $panelBlock->setWidth($panel->width ?? '3');
            $panelBlock->setCssClass($panel->cssclass ?? '');
            $panelBlock->setHtmlTemplateLoader( $this->applicationBuilder->getHtmlTemplateLoader() );

            $panelBlock->setInternalBlockName( $panel->id ?? '' );
            $panelBlock->setBlock( $this->applicationBuilder->getBlock($panel->resource)  );

            $htmlDashboard->addBlockToCurrentRow( $panelBlock );
        }
    }

    return $htmlDashboard;
};
