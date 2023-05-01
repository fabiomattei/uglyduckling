<?php

use Fabiom\UglyDuckling\Common\Blocks\BaseHtmlDoc;

$GLOBALS['myDocFunctions'] = [];

$GLOBALS['myDocFunctions']['dashboard'] = function ( $tableJsonStructure, $jsonLoader ) {
    $doc = new BaseHtmlDoc;
    $docs = [$doc];
    if (count($tableJsonStructure->panels) > 1) {
        $out = 'This page is composed by '. count($tableJsonStructure->panels) . ' sections ';
    }
    foreach ($tableJsonStructure->panels as $panel) {
        if ( $jsonLoader->isJsonResourceIndexedAndFileExists($panel->resource) ) {
            $panelResource = $jsonLoader->loadResource($panel->resource);
            $items = $GLOBALS['myDocFunctions'][$panelResource->metadata->type]($panelResource, $jsonLoader);
            if (is_array($items)) {
                $docs = array_merge($docs, $items);
            } else {
                $docs[] = $items;
            }
        } else {
            echo $panel->resource." does not exist<br>";
        }
    }
    return $docs;
};

$GLOBALS['myDocFunctions']['table'] = function ( $jsonResource, $jsonLoader ) {
    $doc = new BaseHtmlDoc;
    if ( isset($jsonResource->get->table->title) ) {
        $doc->h3($jsonResource->get->table->title);
    }
    if ( isset($jsonResource->description) and is_string($jsonResource->description) ) {
        $doc->paragraph($jsonResource->description);
    }
    if ( isset($jsonResource->docs) and is_array($jsonResource->docs) ) {
        foreach ( $jsonResource->docs as $paragraph) {
            $doc->paragraph($paragraph);
        }
    }
    return $doc;
};

$GLOBALS['myDocFunctions']['datatable'] = function ( $jsonResource, $jsonLoader ) {
    $doc = new BaseHtmlDoc;
    if ( isset($jsonResource->get->table->title) ) {
        $doc->h3($jsonResource->get->table->title);
    }
    if ( isset($jsonResource->description) and is_string($jsonResource->description) ) {
        $doc->paragraph($jsonResource->description);
    }
    if ( isset($jsonResource->docs) and is_array($jsonResource->docs) ) {
        foreach ( $jsonResource->docs as $paragraph) {
            $doc->paragraph($paragraph);
        }
    }
    return $doc;
};

$GLOBALS['myDocFunctions']['form'] = function ($jsonResource, $jsonLoader ) {
    $doc = new BaseHtmlDoc;
    if ( isset($jsonResource->get->form->title) ) {
        $doc->h3($jsonResource->get->form->title);
    }
    if ( isset($jsonResource->description) and is_string($jsonResource->description) ) {
        $doc->paragraph($jsonResource->description);
    }
    if ( isset($jsonResource->docs) and is_array($jsonResource->docs) ) {
        foreach ( $jsonResource->docs as $paragraph) {
            $doc->paragraph($paragraph);
        }
    }
    return $doc;
};

$GLOBALS['myDocFunctions']['info'] = function ($jsonResource, $jsonLoader ) {
    $doc = new BaseHtmlDoc;
    if ( isset($jsonResource->get->info->title) ) {
        $doc->h3($jsonResource->get->info->title);
    }
    if ( isset($jsonResource->description) and is_string($jsonResource->description) ) {
        $doc->paragraph($jsonResource->description);
    }
    if ( isset($jsonResource->docs) and is_array($jsonResource->docs) ) {
        foreach ( $jsonResource->docs as $paragraph) {
            $doc->paragraph($paragraph);
        }
    }
    return $doc;
};

$GLOBALS['myDocFunctions']['chartjs'] = function ($infoJsonStructure, $jsonLoader ) {
    $doc = new BaseHtmlDoc;
    $doc->h1('chartjs');
    if ( isset($jsonResource->description) and is_string($jsonResource->description) ) {
        $doc->paragraph($jsonResource->description);
    }
    if ( isset($jsonResource->docs) and is_array($jsonResource->docs) ) {
        foreach ( $jsonResource->docs as $paragraph) {
            $doc->paragraph($paragraph);
        }
    }
    return $doc;
};
