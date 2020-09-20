<?php

namespace Fabiom\UglyDuckling\Common\Json\JsonTemplates\Info;

use Fabiom\UglyDuckling\Common\Blocks\BaseHTMLInfo;
use Fabiom\UglyDuckling\Common\Database\QueryExecuter;
use Fabiom\UglyDuckling\Common\Json\JsonTemplates\JsonTemplate;

/**
 * User: Fabio Mattei
 * Date: 13/07/18
 * Time: 18.15
 */
class InfoJsonTemplate extends JsonTemplate {

    const blocktype = 'info';

    public function createInfo() {
        $queryExecuter = $this->jsonTemplateFactoriesContainer->getQueryExecuter();
        $queryBuilder = $this->jsonTemplateFactoriesContainer->getQueryBuilder();
        $parameters = $this->jsonTemplateFactoriesContainer->getParameters();
        $dbconnection = $this->jsonTemplateFactoriesContainer->getDbconnection();
        $logger = $this->jsonTemplateFactoriesContainer->getLogger();
        $htmlTemplateLoader = $this->jsonTemplateFactoriesContainer->getHtmlTemplateLoader();
        $pageStatus = $this->jsonTemplateFactoriesContainer->getPageStatus();

        // If there are dummy data they take precedence in order to fill the info box
        if ( isset($this->resource->get->dummydata) ) {
            $entity = $this->resource->get->dummydata;
        } else {
            // If there is a query I look for data to fill the info box,
            // if there is not query I do not
            if ( isset($this->resource->get->query) AND isset($dbconnection) ) {
                $queryExecuter->setDBH( $dbconnection->getDBH() );
				$queryExecuter->setResourceName( $this->resource->name ?? 'undefined ');
                $queryExecuter->setQueryBuilder( $queryBuilder );
                $queryExecuter->setQueryStructure( $this->resource->get->query );
                $queryExecuter->setLogger( $logger );
                $queryExecuter->setPageStatus( $pageStatus );

                $result = $queryExecuter->executeSql();
                $entity = $result->fetch();
            } else {
                $entity = new \stdClass();
            }
        }

        $pageStatus->setLastEntity($entity);

		$infoBlock = new BaseHTMLInfo;
        $infoBlock->setHtmlTemplateLoader( $htmlTemplateLoader );
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
                $value = $pageStatus->getValue($field);
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
