<?php

/**
 * User: Fabio Mattei
 * Date: 13/07/18
 * Time: 18.15
 */

namespace Fabiom\UglyDuckling\Common\Json\JsonTemplates\Info;

use Fabiom\UglyDuckling\Common\Blocks\BaseHTMLInfo;
use Fabiom\UglyDuckling\Common\Database\QueryExecuter;
use Fabiom\UglyDuckling\Common\Json\JsonTemplates\JsonTemplate;

class InfoJsonTemplate extends JsonTemplate {

    const blocktype = 'info';

    public function createInfo() {
        // If there are dummy data they take precedence in order to fill the info box
        if ( isset($this->resource->get->dummydata) ) {
            $entity = $this->resource->get->dummydata;
        } else {
            // If there is a query I look for data to fill the info box,
            // if there is not query I do not
            if ( isset($this->resource->get->query) AND isset($this->dbconnection) ) {
                $this->queryExecuter->setDBH( $this->dbconnection->getDBH() );
                $this->queryExecuter->setQueryBuilder( $this->queryBuilder );
                $this->queryExecuter->setQueryStructure( $this->resource->get->query );
                $this->queryExecuter->setLogger( $this->logger );
                if (isset( $this->parameters ) ) $this->queryExecuter->setGetParameters( $this->parameters );

                $result = $this->queryExecuter->executeSql();
                $entity = $result->fetch();
            } else {
                $entity = new \stdClass();
            }
        }

		$infoBlock = new BaseHTMLInfo;
        $infoBlock->setHtmlTemplateLoader( $this->htmlTemplateLoader );
		$infoBlock->setTitle($this->resource->get->info->title ?? '');
		$fieldRows = array();
		
		foreach ($this->resource->get->info->fields as $field) {
			if( !array_key_exists(($field->row ?? 1), $fieldRows) ) $fieldRows[$field->row] = array();
			$fieldRows[($field->row ?? 1)][] = $field;
		}
		
        $rowcounter = 1;
		foreach ($fieldRows as $row) {
			$infoBlock->addRow();
			foreach ($row as $field) {
				$fieldname = $field->value;
				$value = ($entity == null ? '' : ( isset($entity->$fieldname) ? $entity->$fieldname : '' ) );
                if ($field->type === 'textfield') {
                    $infoBlock->addTextField($field->label, $value, $field->width);
                }
                if ($field->type === 'textarea') {
                    $infoBlock->addTextAreaField($field->label, $value, $field->width);
                }
                if ($field->type === 'currency') {
                    $infoBlock->addCurrencyField($field->label, $value, $field->width);
                }
                if ($field->type === 'date') {
                    $infoBlock->addDateField($field->label, $value, $field->width);
                }
			}
			$infoBlock->closeRow('row '.$rowcounter);
            $rowcounter++;
		}
        return $infoBlock;
    }

}
