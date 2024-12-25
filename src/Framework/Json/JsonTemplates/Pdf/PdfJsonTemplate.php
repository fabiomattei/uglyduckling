<?php

/**
 * Created by Fabio Mattei
 * 
 * Date: 02/11/2018
 * Time: 11:48
 */

namespace Fabiom\UglyDuckling\Framework\Json\JsonTemplates\Pdf;

use Fabiom\UglyDuckling\Framework\Blocks\BaseHTMLTable;
use Fabiom\UglyDuckling\Framework\Json\JsonTemplates\JsonTemplate;

class PdfJsonTemplate extends JsonTemplate {

    /**
     * PdfJsonTemplate constructor.
     * @param $applicationBuilder
     * @param $pageStatus
     */
    function __construct( $jsonResource, $pageStatus, $resourcesIndex, $jsonResourceTemplates, $jsonTabTemplates ) {
        parent::__construct( $jsonResource, $pageStatus, $resourcesIndex, $jsonResourceTemplates, $jsonTabTemplates);
    }

    public function createTable() {
        $htmlTemplateLoader = $this->applicationBuilder->getHtmlTemplateLoader();
        $queryExecutor = $this->pageStatus->getQueryExecutor();

        // If there are dummy data they take precedence in order to fill the table
        if ( isset($this->resource->get->dummydata) ) {
            $entities = $this->resource->get->dummydata;
        } else {
            // If there is a query I look for data to fill the table,
            // if there is not query I do not
            if ( isset($this->resource->get->query) ) {
                $queryExecutor->setResourceName( $this->resource->name ?? 'undefined ');
                $queryExecutor->setQueryStructure( $this->resource->get->query );
                $entities = $queryExecutor->executeSql();
            }
        }

        $table = $this->resource->get->table;

        $tableBlock = new BaseHTMLTable;
        $tableBlock->setHtmlTemplateLoader( $htmlTemplateLoader );
        $tableBlock->setTitle($table->title ?? '');

        $tableBlock->addTHead();
        $tableBlock->addRow();
        foreach ($table->fields as $field) {
            $tableBlock->addHeadLineColumn($field->headline);
        }
        $tableBlock->closeRow();
        $tableBlock->closeTHead();

        $tableBlock->addTBody();
        foreach ($entities as $entity) {
            $this->pageStatus->setLastEntity($entity);
            $tableBlock->addRow();
            foreach ($table->fields as $field) {
                $tableBlock->addColumn($this->pageStatus->getValue($field));
            }
            $tableBlock->closeRow();
        }
        $tableBlock->closeTBody();

        return $tableBlock->show();
    }
}
