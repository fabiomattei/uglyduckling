<?php

/**
 * User: fabio
 * Date: 14/07/2018
 * Time: 07:02
 */

namespace Firststep\Common\Builders;

class QueryBuilder {
	
    private $queryStructure;
    private $parameters;

    /**
     * @param mixed $queryStructure
     */
    public function setQueryStructure($queryStructure) {
        $this->queryStructure = $queryStructure;
    }

    /**
     * @param mixed $parameters
	 * the $parameters variable contains all values for the query
     */
    public function setParameters($parameters) {
        $this->parameters = $parameters;
    }

    public function createQuery() {
		if($this->queryStructure->type === 'select') {
			return $this->select();
		}
		if($this->queryStructure->type === 'insert') {
			return $this->insert();
		}
		if($this->queryStructure->type === 'update') {
			return $this->update();
		}
		if($this->queryStructure->type === 'delete') {
			return $this->delete();
		} 
    }

    public function select() {
    	$query = 'SELECT ';
    	foreach ($this->queryStructure->fields as $field) {
    		$query .= $field.', ';
    	}
    	$query=rtrim($query,', ');
    	$query .= ' FROM '.$this->queryStructure->entity.' ';
    	foreach ($this->queryStructure->joins as $join) {
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
    	if (count($this->queryStructure->conditions)>0) {
    		$query .= ' WHERE ';
    	}
    	foreach ($this->queryStructure->conditions as $cond) {
    		$query .= $cond->field.' '.$cond->operator.' :'.$cond->value.', ';
    	}
        $query=rtrim($query,', ');
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
