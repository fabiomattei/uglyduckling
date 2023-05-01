<?php

use Fabiom\UglyDuckling\Common\Blocks\BaseHtmlDoc;

$GLOBALS['myDocFunctions'] = [];

$GLOBALS['myDocFunctions']['dashboard'] = function ( $jsonResource, $jsonLoader ) {
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
        $doc->paragraph('This page is composed by '. count($jsonResource->panels) . ' sections ');
    }
    if (isset($jsonResource->docimg) and is_string($jsonResource->docimg) ) {
        $doc->img($jsonResource->docimg, ['width'=>'700']);
    }
    foreach ($jsonResource->panels as $panel) {
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
    if (isset($jsonResource->docprocess) and is_array($jsonResource->docprocess)) {
        foreach ($jsonResource->docprocess as $processResourceName) {
            if ( $jsonLoader->isJsonResourceIndexedAndFileExists($processResourceName) ) {
                $panelResource = $jsonLoader->loadResource($processResourceName);
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
    }
    return $docs;
};

$GLOBALS['myDocFunctions']['table'] = function ( $jsonResource, $jsonLoader ) {
    $doc = new BaseHtmlDoc;
    $docs = [$doc];

    if ( isset($jsonResource->get->table->title) ) {
        $doc->h4($jsonResource->get->table->title);
    }
    if ( isset($jsonResource->description) and is_string($jsonResource->description) ) {
        $doc->paragraph($jsonResource->description);
    }
    if ( isset($jsonResource->docs) and is_array($jsonResource->docs) ) {
        foreach ( $jsonResource->docs as $paragraph) {
            $doc->paragraph($paragraph);
        }
    }
    if (isset($jsonResource->docimg) and is_string($jsonResource->docimg) ) {
        $doc->img($jsonResource->docimg, ['width'=>'700']);
    }

    if (isset($jsonResource->docprocess) and is_array($jsonResource->docprocess)) {
        foreach ($jsonResource->docprocess as $processResourceName) {
            if ( $jsonLoader->isJsonResourceIndexedAndFileExists($processResourceName) ) {
                $panelResource = $jsonLoader->loadResource($processResourceName);
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
    }

    return $docs;
};

$GLOBALS['myDocFunctions']['datatable'] = function ( $jsonResource, $jsonLoader ) {
    $doc = new BaseHtmlDoc;
    $docs = [$doc];

    if ( isset($jsonResource->get->table->title) ) {
        $doc->h4($jsonResource->get->table->title);
    }
    if ( isset($jsonResource->description) and is_string($jsonResource->description) ) {
        $doc->paragraph($jsonResource->description);
    }
    if ( isset($jsonResource->docs) and is_array($jsonResource->docs) ) {
        foreach ( $jsonResource->docs as $paragraph) {
            $doc->paragraph($paragraph);
        }
    }
    if (isset($jsonResource->docimg) and is_string($jsonResource->docimg) ) {
        $doc->img($jsonResource->docimg, ['width'=>'700']);
    }
    if (isset($jsonResource->docprocess) and is_array($jsonResource->docprocess)) {
        foreach ($jsonResource->docprocess as $processResourceName) {
            if ( $jsonLoader->isJsonResourceIndexedAndFileExists($processResourceName) ) {
                $panelResource = $jsonLoader->loadResource($processResourceName);
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
    }
    return $docs;
};

$GLOBALS['myDocFunctions']['form'] = function ($jsonResource, $jsonLoader ) {
    $doc = new BaseHtmlDoc;
    $docs = [$doc];

    if ( isset($jsonResource->get->form->title) ) {
        $doc->h4($jsonResource->get->form->title);
    }
    if ( isset($jsonResource->description) and is_string($jsonResource->description) ) {
        $doc->paragraph($jsonResource->description);
    }
    if ( isset($jsonResource->docs) and is_array($jsonResource->docs) ) {
        foreach ( $jsonResource->docs as $paragraph) {
            $doc->paragraph($paragraph);
        }
    }
    if (isset($jsonResource->docimg) and is_string($jsonResource->docimg) ) {
        $doc->img($jsonResource->docimg, ['width'=>'700']);
    }
    if (isset($jsonResource->docprocess) and is_array($jsonResource->docprocess)) {
        foreach ($jsonResource->docprocess as $processResourceName) {
            if ( $jsonLoader->isJsonResourceIndexedAndFileExists($processResourceName) ) {
                $panelResource = $jsonLoader->loadResource($processResourceName);
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
    }
    return $docs;
};

$GLOBALS['myDocFunctions']['info'] = function ($jsonResource, $jsonLoader ) {
    $doc = new BaseHtmlDoc;
    $docs = [$doc];

    if ( isset($jsonResource->get->info->title) ) {
        $doc->h4($jsonResource->get->info->title);
    }
    if ( isset($jsonResource->description) and is_string($jsonResource->description) ) {
        $doc->paragraph($jsonResource->description);
    }
    if ( isset($jsonResource->docs) and is_array($jsonResource->docs) ) {
        foreach ( $jsonResource->docs as $paragraph) {
            $doc->paragraph($paragraph);
        }
    }
    if (isset($jsonResource->docimg) and is_string($jsonResource->docimg) ) {
        $doc->img($jsonResource->docimg, ['width'=>'700']);
    }
    if (isset($jsonResource->docprocess) and is_array($jsonResource->docprocess)) {
        foreach ($jsonResource->docprocess as $processResourceName) {
            if ( $jsonLoader->isJsonResourceIndexedAndFileExists($processResourceName) ) {
                $panelResource = $jsonLoader->loadResource($processResourceName);
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
    }
    return $docs;
};

$GLOBALS['myDocFunctions']['chartjs'] = function ($infoJsonStructure, $jsonLoader ) {
    $doc = new BaseHtmlDoc;
    $docs = [$doc];

    $doc->h1('chartjs');
    if ( isset($jsonResource->description) and is_string($jsonResource->description) ) {
        $doc->paragraph($jsonResource->description);
    }
    if ( isset($jsonResource->docs) and is_array($jsonResource->docs) ) {
        foreach ( $jsonResource->docs as $paragraph) {
            $doc->paragraph($paragraph);
        }
    }
    if (isset($jsonResource->docimg) and is_string($jsonResource->docimg) ) {
        $doc->img($jsonResource->docimg, ['width'=>'700']);
    }
    if (isset($jsonResource->docprocess) and is_array($jsonResource->docprocess)) {
        foreach ($jsonResource->docprocess as $processResourceName) {
            if ( $jsonLoader->isJsonResourceIndexedAndFileExists($processResourceName) ) {
                $panelResource = $jsonLoader->loadResource($processResourceName);
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
    }
    return $docs;
};
