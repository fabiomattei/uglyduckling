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
		
		if ( isset( $this->queryStructure->joins ) ) {
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
		}
		
    	if (count($this->queryStructure->conditions)>0) {
    		$query .= ' WHERE ';
    	}
		if ( isset( $this->queryStructure->conditions ) ) {
    		foreach ($this->queryStructure->conditions as $cond) {
    			$query .= $cond->field.' '.$cond->operator.' :'.$cond->value.', ';
    		}
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

	/**
     * You can use show tables like this to see if a single table exists:
     * 
     * mysql> show tables like "test1";
     * which would return:
     * 
     * +------------------------+
     * | Tables_in_test (test1) |
     * +------------------------+
     * | test1                  |
     * +------------------------+
     * 1 row in set (0.00 sec)
     * 
     * If you ran show tables on a table that didn't exist you would get this:
     * 
     * mysql> show tables like "test3";
     * Empty set (0.01 sec)
	 */
    public function tableExists( $tablename ) {
        return 'SHOW TABLES LIKE \'' . $tablename . '\';';
    }
	
    public function tableDrop( $tablename ) {
        return 'DROP TABLE ' . $tablename . ';';
    }

    public function create() {
        $query = 'CREATE TABLE `'.$this->queryStructure->tablename.'` (';
    	foreach ($this->queryStructure->fields as $field) {
    		$query .= '`'.$field->name.'` '.$field->type.', ';
    	}
		$query=rtrim($query,', ');
		$query .= ') ENGINE='.$this->queryStructure->engine.' CHARSET='.$this->queryStructure->charset.' COLLATE='.$this->queryStructure->collate.';';
		return $query;
    }
	
	/**
	 * ALTER TABLE `projectmessage` ADD PRIMARY KEY (`prjme_id`);
	 */
	public function primarykey() {
		return 'ALTER TABLE `'.$this->queryStructure->tablename.'` ADD PRIMARY KEY (`'.$this->queryStructure->primary.'`);';
	}
	
	/**
	 * ALTER TABLE `useroffice` MODIFY `usroff_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
	 */
	public function autoincrement() {
		$type = 'int(10) UNSIGNED NOT NULL';
    	foreach ($this->queryStructure->fields as $field) {
			if ( $field->name == $this->queryStructure->primary ) $type = $field->type;
    	}
		$type .= ' AUTO_INCREMENT';
		return 'ALTER TABLE `'.$this->queryStructure->tablename.'` MODIFY `'.$this->queryStructure->primary.'` '.$type.' ;';
	}
	
	/**
	 * ALTER TABLE `nontechnicalasset`
  	 *     ADD CONSTRAINT `nontechnicalasset_ibfk_1` FOREIGN KEY (`nta_categoryid`) REFERENCES `assetcategory` (`ac_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  	 *     ADD CONSTRAINT `nontechnicalasset_ibfk_2` FOREIGN KEY (`nta_parentid`) REFERENCES `asset` (`a_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  	 *     ADD CONSTRAINT `nontechnicalasset_ibfk_3` FOREIGN KEY (`nta_unitid`) REFERENCES `riskcentreunit` (`rcu_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  	 *     ADD CONSTRAINT `nontechnicalasset_ibfk_4` FOREIGN KEY (`nta_baseid`) REFERENCES `asset` (`a_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
	 */
	public function addkey() {
		$constraintsString = '';
		foreach ($this->queryStructure->constraints as $cons) {
			$constraintsString .= 'ADD CONSTRAINT `'.$cons->name.'` FOREIGN KEY (`'.$cons->foreignkey.'`) REFERENCES `'.$cons->referencestable.'` (`'.$cons->referencesfield.'`) ON DELETE '.$cons->ondelete.' ON UPDATE '.$cons->onupdate.',';
		}
		$constraintsString = rtrim($constraintsString,','); 
		$constraintsString .= ';';
		return 'ALTER TABLE `'.$this->queryStructure->tablename.'` '.$constraintsString.';';
	}
	
    public function count() {
        # code...
    }

}
