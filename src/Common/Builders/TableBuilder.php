<?php

/**
 * User: fabio
 * Date: 13/07/2018
 * Time: 20:39
 */

namespace Firststep\Common\Builders;

use Firststep\Common\Blocks\StaticTable;

class TableBuilder {
	
    private $tableStructure;
    private $entities;

    /**
     * @param mixed $tableStructure
     */
    public function setTableStructure($tableStructure) {
        $this->tableStructure = $tableStructure;
    }

    /**
     * @param mixed $entities
	 * the $entities variable contains all values for the table
     */
    public function setEntities($entities) {
        $this->entities = $entities;
    }

    public function setRouter( $router ) {
    	$this->router = $router;
    }

    public function createTable() {
		$tableBlock = new StaticTable;
		$tableBlock->setTitle($this->tableStructure->title);
		
		$tableBlock->addTHead();
		$tableBlock->addRow();
		foreach ($this->tableStructure->fields as $field) {
			$tableBlock->addHeadLineColumn($field->headline);
		}
		$tableBlock->addHeadLineColumn(''); // adding one more for actions
		$tableBlock->closeRow();
		$tableBlock->closeTHead();
		
		$tableBlock->addTBody();
		foreach ($this->entities as $entity) {
			$tableBlock->addRow();
			foreach ($this->tableStructure->fields as $field) {
				$tableBlock->addColumn($entity->{$field->sqlfield});
			}
			$links = '';
			foreach ( $this->tableStructure->actions as $action ) {
				$links .= LinkBuilder::get( $this->router, $action->lable, $action->action, $action->resource, $action->parameters, $entity );
			}
			$tableBlock->addUnfilteredColumn( $links );
			$tableBlock->closeRow();
		}
		$tableBlock->closeTBody();
		
        return $tableBlock;
    }
	
}
