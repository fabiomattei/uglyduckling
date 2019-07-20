<?php

/**
 * Created by Fabio Mattei
 * User: fabio
 * Date: 02/11/2018
 * Time: 11:48
 */

namespace Fabiom\UglyDuckling\Common\Json\JsonTemplates\Pdf;

use Fabiom\UglyDuckling\Common\Blocks\BaseHTMLTable;
use Fabiom\UglyDuckling\Common\Database\QueryExecuter;
use Fabiom\UglyDuckling\Common\Json\JsonTemplates\QueryBuilder;
use Fabiom\UglyDuckling\Common\Json\JsonTemplates\JsonTemplate;

class PdfJsonTemplate extends JsonTemplate {

    function __construct() {
        $this->queryExecuter = new QueryExecuter;
        $this->queryBuilder = new QueryBuilder;
    }

    /**
     * @param mixed $parameters
     */
    public function setParameters($parameters) {
        $this->parameters = $parameters;
    }

    /**
     * @param mixed $resource
     */
    public function setResource($resource) {
        $this->resource = $resource;
    }

    /**
     * @param mixed $dbconnection
     */
    public function setDbconnection($dbconnection) {
        $this->dbconnection = $dbconnection;
    }

    public function createTable() {
        $this->queryExecuter->setDBH( $this->dbconnection->getDBH() );
        $this->queryExecuter->setQueryBuilder( $this->queryBuilder );
        $this->queryExecuter->setQueryStructure( $this->resource->post->query );
        if (isset( $this->parameters ) ) $this->queryExecuter->setPostParameters( $this->parameters );
        $entities = $this->queryExecuter->executeQuery();

        $table = $this->resource->post->table;

        $tableBlock = new BaseHTMLTable;
        $tableBlock->setHtmlTemplateLoader( $this->htmlTemplateLoader );
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
            $tableBlock->addRow();
            foreach ($table->fields as $field) {
                $tableBlock->addColumn($entity->{$field->sqlfield});
            }
            $tableBlock->closeRow();
        }
        $tableBlock->closeTBody();

        return $tableBlock->show();
    }
}
