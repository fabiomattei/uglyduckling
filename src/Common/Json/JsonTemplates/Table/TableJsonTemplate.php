<?php

/**
 * User: Fabio Mattei
 * Date: 13/07/2018
 * Time: 20:39
 */

namespace Fabiom\UglyDuckling\Common\Json\JsonTemplates\Table;

use Fabiom\UglyDuckling\Common\Blocks\BaseHTMLTable;
use Fabiom\UglyDuckling\Common\Blocks\EmptyHTMLBlock;
use Fabiom\UglyDuckling\Common\Json\JsonTemplates\JsonTemplate;

class TableJsonTemplate extends JsonTemplate {

    const blocktype = 'table';
    const GET_METHOD = 'GET';
    private $query;
    private $method;

    /**
     * TableJsonTemplate constructor.
     * @param $applicationBuilder
     * @param $pageStatus
     */
    function __construct( $applicationBuilder, $pageStatus ) {
        parent::__construct( $applicationBuilder, $pageStatus);
        $this->query = '';
        $this->method = self::GET_METHOD;
    }

    /**
     * @param string $method
     * refers to http method: GET or POST
     */
    public function setMethod(string $method) {
        $this->method = $method;
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
		$tableBlock->addHeadLineColumn(''); // adding one more for actions
		$tableBlock->closeRow();
		$tableBlock->closeTHead();
		
		$tableBlock->addTBody();
		foreach ($entities as $entity) {
            $this->pageStatus->setLastEntity($entity);
			$tableBlock->addRow();
			foreach ($table->fields as $field) {
				$tableBlock->addColumn( $pageStatus->getValue($field) );
			}
			$links = '';
            if (isset($table->actions) AND is_array($table->actions)) {
                foreach ( $table->actions as $action ) {
                    $links .= $applicationBuilder->getHTMLTag( $action, $this->pageStatus, $this->applicationBuilder );
                }
            }
			$tableBlock->addUnfilteredColumn( $links );
			$tableBlock->closeRow();
		}
		$tableBlock->closeTBody();
		
        return $tableBlock;
    }

    /**
     * Return a object that inherit from BaseHTMLBlock class
     * It is an object that has to generate HTML code
     *
     * @return EmptyHTMLBlock
     */
    public function createHTMLBlock() {
        return $this->createTable();
    }

}
