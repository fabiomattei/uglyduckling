<?php

/**
 * User: fabio
 * Date: 14/07/2018
 * Time: 07:02
 */

namespace Fabiom\UglyDuckling\Common\Json\JsonTemplates;

/**
 * @deprecated
 *
 * Class QueryBuilder
 * @package Fabiom\UglyDuckling\Common\Json\JsonTemplates
 */
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
