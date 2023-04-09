<?php

$myDocFunctions = [];

$myDocFunctions['dashboard'] = function ( $tableJsonStructure, $jsonLoader ) {
    $out = '';
    foreach ($tableJsonStructure->panels as $panel) {
        if ( $jsonLoader->isJsonResourceIndexedAndFileExists($panel->resource) ) {
            $panelResource = $jsonLoader->loadResource($panel->resource);
            $out += $GLOBALS['myDocFunctions'][$panelResource->metadata->type]($panelResource, $jsonLoader);
        }
    }
    return $out;
};

$myDocFunctions['table'] = function ( $tableJsonStructure, $jsonLoader ) {
    if ( isset($tableJsonStructure->get->table->title) ) {
        return $tableJsonStructure->get->table->title;
    }
    return '';
};

$myDocFunctions['form'] = function ($formJsonStructure, $jsonLoader ) {
    if ( isset($formJsonStructure->get->form->title) ) {
        return $formJsonStructure->get->form->title;
    }
    return '';
};

$myDocFunctions['info'] = function ($infoJsonStructure, $jsonLoader ) {
    if ( isset($infoJsonStructure->get->info->title) ) {
        return $infoJsonStructure->get->info->title;
    }
    return '';
};



