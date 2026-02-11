<?php

namespace Fabiom\UglyDuckling\Framework\Json\JsonTemplates\Info;

use Fabiom\UglyDuckling\Framework\Blocks\BaseHTMLInfo;
use Fabiom\UglyDuckling\Framework\Json\JsonTemplates\JsonDefaultTemplateFactory;
use Fabiom\UglyDuckling\Framework\Json\JsonTemplates\JsonTemplate;

/**
 * User: Fabio Mattei
 * Date: 13/07/18
 * Time: 18.15
 */
class InfoJsonTemplate extends JsonTemplate {

    const blocktype = 'info';

    public function createInfo() {
        $queryExecutor = $this->pageStatus->getQueryExecutor();

        // If there are dummy data they take precedence in order to fill the info box
        if ( isset($this->resource->get->dummydata) ) {
            $entity = $this->resource->get->dummydata;
        } else {
            // If there is a query I look for data to fill the info box,
            // if there is not query I do not
            if ( isset($this->resource->get->query) ) {
                $queryExecutor->setResourceName( $this->resource->name ?? 'undefined ');
                $queryExecutor->setQueryStructure( $this->resource->get->query );

                $result = $queryExecutor->executeSql();
                $entity = $result->fetch();
            } else {
                $entity = new \stdClass();
            }

            if ( isset($this->resource->get->titlequery) ) {
                $queryExecutor->setResourceName( $this->resource->name ?? 'undefined ');
                $queryExecutor->setQueryStructure( $this->resource->get->titlequery );

                $result = $queryExecutor->executeSql();
                $titleentity = $result->fetch();
            } else {
                $titleentity = new \stdClass();
            }
        }

		$infoBlock = new BaseHTMLInfo;
        if ( isset( $this->resource->get->titlequery ) ) {
            $this->pageStatus->setLastEntity($titleentity);
            $value = $this->pageStatus->getValue($this->resource->get->info->title);
            $infoBlock->setTitle($value);
        } else {
            $infoBlock->setTitle($this->resource->get->info->title ?? '');
        }

        $this->pageStatus->setLastEntity($entity);
        $fieldRows = [];

        foreach ($this->resource->get->info->fields as $field) {
            if( !array_key_exists(($field->row ?? 1), $fieldRows) ) $fieldRows[$field->row] = array();
            $fieldRows[($field->row ?? 1)][] = $field;
        }
		
        $rowcounter = 1;
		foreach ($fieldRows as $row) {
			$infoBlock->addRow();
			foreach ($row as $field) {
                $value = $this->pageStatus->getValue($field);
                if ($field->type === 'textfield') {
                    $infoBlock->addTextField($field->label, $value, $field->width ?? '', $field->cssclass ?? '');
                }
                elseif ($field->type === 'textarea') {
                    $infoBlock->addTextAreaField($field->label, $value, $field->width ?? '', $field->cssclass ?? '');
                }
                elseif ($field->type === 'currency') {
                    $infoBlock->addCurrencyField($field->label, $value, $field->width ?? '', $field->cssclass ?? '');
                }
                elseif ($field->type === 'date') {
                    $infoBlock->addDateField($field->label, $value, $field->width ?? '', $field->cssclass ?? '');
                }
			}
			$infoBlock->closeRow('row '.$rowcounter);
            $rowcounter++;
		}
        // $topButtons = '';
        // if (isset($this->resource->get->info->topbuttons) AND is_array($this->resource->get->info->topbuttons)) {
        //     foreach ($this->resource->get->info->topbuttons as $button) {
        //         $topButtons .= JsonDefaultTemplateFactory::getHTMLTag( $button, $this->pageStatus, $this->jsonTabTemplates );
        //     }
        // }
        // $infoBlock->setTopButtons($topButtons);
        // $bottomButtons = '';
        // if (isset($this->resource->get->info->bottombuttons) AND is_array($this->resource->get->info->bottombuttons)) {
        //     foreach ($this->resource->get->info->bottombuttons as $button) {
        //         $bottomButtons .= JsonDefaultTemplateFactory::getHTMLTag( $button, $this->pageStatus, $this->jsonTabTemplates );
        //     }
        // }
        // $infoBlock->setBottomButtons($bottomButtons);
        return $infoBlock;
    }

}
