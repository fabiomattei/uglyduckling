<?php

use Fabiom\UglyDuckling\Common\Blocks\BaseHtmlDoc;

$GLOBALS['myDocFunctions'] = [];

$GLOBALS['myDocFunctions']['dashboard'] = function ( $jsonResource, $jsonLoader, $breadcrumb, $level ) {
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

$GLOBALS['myDocFunctions']['table'] = function ( $jsonResource, $jsonLoader, $breadcrumb, $level ) {
    $doc = new BaseHtmlDoc;
    $docs = [$doc];

    $chapterNumber = 1;
    if ( isset($jsonResource->get->table->title) AND trim($jsonResource->get->table->title) != '' ) {
        if ($level = 2) {
            $doc->h4($breadcrumb .'.'.$chapterNumber .  ' ' . $jsonResource->get->table->title);
        } elseif ($level = 3) {
            $doc->h5($breadcrumb . ' ' . $jsonResource->get->table->title);
        } else {
            $doc->h6($breadcrumb . ' ' . $jsonResource->get->table->title);
        }
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
            $chapterNumber += 1;
            if ( $jsonLoader->isJsonResourceIndexedAndFileExists($processResourceName) ) {
                $panelResource = $jsonLoader->loadResource($processResourceName);
                $items = $GLOBALS['myDocFunctions'][$panelResource->metadata->type]($panelResource, $jsonLoader, $breadcrumb . '.' . $chapterNumber, $level + 1);
                if (is_array($items)) {
                    $docs = array_merge($docs, $items);
                } else {
                    $docs[] = $items;
                }
            } else {
                echo $processResourceName." does not exist<br>";
            }
        }
    }

    return $docs;
};

$GLOBALS['myDocFunctions']['datatable'] = function ( $jsonResource, $jsonLoader, $breadcrumb, $level ) {
    $doc = new BaseHtmlDoc;
    $docs = [$doc];

    if ( isset($jsonResource->get->table->title) ) {
        if ($level = 2) {
            $doc->h4($breadcrumb . ' ' . $jsonResource->get->table->title);
        } elseif ($level = 3) {
            $doc->h5($breadcrumb . ' ' . $jsonResource->get->table->title);
        } else {
            $doc->h6($breadcrumb . ' ' . $jsonResource->get->table->title);
        }
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
    $chapterNumber = 1;
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
                echo $processResourceName." does not exist<br>";
            }
            $chapterNumber += 1;
        }
    }
    return $docs;
};

$GLOBALS['myDocFunctions']['form'] = function ($jsonResource, $jsonLoader, $breadcrumb, $level ) {
    $doc = new BaseHtmlDoc;
    $docs = [$doc];

    if ( isset($jsonResource->get->form->title) ) {
        if ($level = 2) {
            $doc->h4($breadcrumb . ' ' . $jsonResource->get->form->title);
        } elseif ($level = 3) {
            $doc->h5($breadcrumb . ' ' . $jsonResource->get->form->title);
        } else {
            $doc->h6($breadcrumb . ' ' . $jsonResource->get->form->title);
        }
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
    $chapterNumber = 1;
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
                echo $processResourceName." does not exist<br>";
            }
            $chapterNumber += 1;
        }
    }
    return $docs;
};

$GLOBALS['myDocFunctions']['info'] = function ($jsonResource, $jsonLoader, $breadcrumb, $level ) {
    $doc = new BaseHtmlDoc;
    $docs = [$doc];

    if ( isset($jsonResource->get->info->title) ) {
        if ($level = 2) {
            $doc->h4($breadcrumb . ' ' . $jsonResource->get->info->title);
        } elseif ($level = 3) {
            $doc->h5($breadcrumb . ' ' . $jsonResource->get->info->title);
        } else {
            $doc->h6($breadcrumb . ' ' . $jsonResource->get->info->title);
        }
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
    $chapterNumber = 1;
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
                echo $processResourceName." does not exist<br>";
            }
            $chapterNumber += 1;
        }
    }
    return $docs;
};

$GLOBALS['myDocFunctions']['chartjs'] = function ($jsonResource, $jsonLoader, $breadcrumb, $level ) {
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
    if (isset($jsonResource->docimg) and is_string($jsonResource->docimg) ) {
        $doc->img($jsonResource->docimg, ['width'=>'700']);
    }
    $chapterNumber = 1;
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
                echo $processResourceName." does not exist<br>";
            }
            $chapterNumber += 1;
        }
    }
    return $docs;
};

$GLOBALS['myDocFunctions']['ismiform'] = $GLOBALS['myDocFunctions']['form'];
$GLOBALS['myDocFunctions']['coloreddatatable'] = $GLOBALS['myDocFunctions']['datatable'];

