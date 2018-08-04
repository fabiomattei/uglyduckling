<?php

/**
 * User: fabio
 * Date: 14/07/2018
 * Time: 07:02
 */

namespace Firststep\Common\Builders;

class QueryBuilder {
	
    private $queryStructure;
    private $entity;

    /**
     * @param mixed $queryStructure
     */
    public function setQueryStructure($queryStructure) {
        $this->queryStructure = $queryStructure;
    }

    /**
     * @param mixed $entity
	 * the $entity variable contains all values for the form
     */
    public function setEntity($entity) {
        $this->entity = $entity;
    }

    public function createQuery() {
		if($queryStructure->type === 'query') {
			return $this->select();
		}
		if($queryStructure->type === 'insert') {
			return $this->insert();
		}
		if($queryStructure->type === 'update') {
			return $this->update();
		}
		if($queryStructure->type === 'delete') {
			return $this->delete();
		} 
    }

    public function select() {
    	$query = 'SELECT ';
    	foreach ($queryStructure->fields as $field) {
    		$query .= $field.', ';
    	}
    	$query=rtrim($query,', ');
    	$query .= ' FROM '.$queryStructure->entity;
    	foreach ($queryStructure->joins as $join) {
    		if ($join->type == 'left') {
    			$query .= ' LEFT JOIN ';	
    		}
    		if ($join->type == 'right') {
    			$query .= ' RIGHT JOIN ';	
    		}
    		if ($join->type == 'inner') {
    			$query .= ' INNER JOIN ';	
    		}
    		if ($join->type == 'outer') {
    			$query .= ' FULL OUTER JOIN ';	
    		}
    		if ($join->type == 'join') {
    			$query .= ' JOIN ';	
    		}
    		$query .= $join->entity.' ON '.$join->joinon;
    	}
    	if (count($queryStructure->conditions)>0) {
    		$query .= ' WHERE ';
    	}
    	foreach ($queryStructure->conditions as $cond) {
    		$query .= $cond->field.' '.$cond->operator.' '.$cond->value;
    	}
        return $query;
    }

    public function insert() {
    	# code...
    }

	public function update() {
    	# code...
    }

    public function delete() {
    	# code...
    }

    public function tableExists() {
        # code...
    }

    public function create() {
        # code...
    }

    public function count() {
        # code...
    }

}
