<?php

/**
 * User: Fabio Mattei
 * Date: 13/07/2018
 * Time: 20:39
 */

namespace Fabiom\UglyDuckling\Common\Json\JsonTemplates\Table;

use Fabiom\UglyDuckling\Common\Blocks\BaseHTMLTable;
use Fabiom\UglyDuckling\Common\Json\JsonTemplates\JsonTemplate;

/**
 * This class cares about creating a basic table for a UD application.
 *
 * Check the documentation
 * http://www.uddocs.com/docs/table-page
 */
class TableJsonTemplate extends JsonTemplate {

    const blocktype = 'table';

    /**
     * TableJsonTemplate constructor.
     * @param $applicationBuilder
     * @param $pageStatus
     */
    function __construct( $applicationBuilder, $pageStatus ) {
        parent::__construct( $applicationBuilder, $pageStatus);
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

            if ( isset($this->resource->get->titlequery) ) {
                $queryExecutor->setResourceName( $this->resource->name ?? 'undefined ');
                $queryExecutor->setQueryStructure( $this->resource->get->titlequery );
                $result_title = $queryExecutor->executeSql();
                $title_entity = $result_title->fetch();
            }
        }

        $table = $this->resource->get->table;

		$tableBlock = new BaseHTMLTable;
        $tableBlock->setHtmlTemplateLoader( $htmlTemplateLoader );

        if ( isset( $title_entity ) AND $title_entity != '' AND is_object($table->title) ) {
            $this->pageStatus->setLastEntity($title_entity);
            $tableBlock->setTitle($this->pageStatus->getValue($table->title));
        } else {
            $tableBlock->setTitle($table->title ?? '');
        }
		
		$tableBlock->addTHead();
		$tableBlock->addRow();
		foreach ($table->fields as $field) {
			$tableBlock->addHeadLineColumn($field->headline);
		}
		$tableBlock->addHeadLineColumn(''); // adding one more for actions
		$tableBlock->closeRow();
		$tableBlock->closeTHead();
		
		$tableBlock->addTBody();
		foreach ($entities as $entity) {
            $this->pageStatus->setLastEntity($entity);
			$tableBlock->addRow();
            foreach ($table->fields as $field) {
                $tableBlock->addColumn( $this->pageStatus->getValue($field) );
            }
			$links = '';
            if (isset($table->actions) AND is_array($table->actions)) {
                foreach ( $table->actions as $action ) {
                    $links .= $this->applicationBuilder->getHTMLTag( $action, $this->pageStatus, $this->applicationBuilder );
                }
            }
			$tableBlock->addUnfilteredColumn( $links );
			$tableBlock->closeRow();
		}
		$tableBlock->closeTBody();

        $topActions = '';
        if (isset($this->resource->get->table->topactions)) {
            foreach( $this->resource->get->table->topactions as $action ) {
                $topActions .= $this->applicationBuilder->getHTMLTag( $action, $this->pageStatus, $this->applicationBuilder );
            }
        }
        $tableBlock->setTopActions($topActions);

        $bottomActions = '';
        if (isset($this->resource->get->table->bottomactions)) {
            foreach( $this->resource->get->table->bottomactions as $action ) {
                $bottomActions .= $this->applicationBuilder->getHTMLTag( $action, $this->pageStatus, $this->applicationBuilder );
            }
        }
        $tableBlock->setBottomActions($bottomActions);
		
        return $tableBlock;
    }

    /**
     * Return a object that inherit from BaseHTMLBlock class
     * It is an object that has to generate HTML code
     *
     * @return BaseHTMLTable
     */
    public function createHTMLBlock() {
        return $this->createTable();
    }

}