<?php

$GLOBALS['myDocFunctions'] = [];

$GLOBALS['myDocFunctions']['dashboard'] = function ( $tableJsonStructure, $jsonLoader ) {
    $out = 'This page is composed by '. count($tableJsonStructure->panels) . ' sections ';
    foreach ($tableJsonStructure->panels as $panel) {
        if ( $jsonLoader->isJsonResourceIndexedAndFileExists($panel->resource) ) {
            $panelResource = $jsonLoader->loadResource($panel->resource);
            $out .= $GLOBALS['myDocFunctions'][$panelResource->metadata->type]($panelResource, $jsonLoader);
        } else {
            $out .= $panel->resource;
        }
    }
    return $out;
};

$GLOBALS['myDocFunctions']['table'] = function ( $tableJsonStructure, $jsonLoader ) {
    if ( isset($tableJsonStructure->get->table->title) ) {
        return $tableJsonStructure->get->table->title;
    }
    return 'table';
};

$GLOBALS['myDocFunctions']['datatable'] = function ( $tableJsonStructure, $jsonLoader ) {
    if ( isset($tableJsonStructure->get->table->title) ) {
        return $tableJsonStructure->get->table->title;
    }
    return 'datatable';
};

$GLOBALS['myDocFunctions']['form'] = function ($formJsonStructure, $jsonLoader ) {
    if ( isset($formJsonStructure->get->form->title) ) {
        return $formJsonStructure->get->form->title;
    }
    return 'form';
};

$GLOBALS['myDocFunctions']['info'] = function ($infoJsonStructure, $jsonLoader ) {
    if ( isset($infoJsonStructure->get->info->title) ) {
        return $infoJsonStructure->get->info->title;
    }
    return 'info';
};

$GLOBALS['myDocFunctions']['chartjs'] = function ($infoJsonStructure, $jsonLoader ) {
    return 'chartjs';
};
