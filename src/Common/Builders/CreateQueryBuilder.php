<?php

/**
 * User: fabio
 * Date: 13/10/2018
 * Time: 09:05
 *
 * This class takes data loaded through a specific structure and generates the queries necessary in order to create a table
 * 
 * Data needed:
 * table_name: contains a string with the name of the table
 * engine:
 * charset
 * collate:
 * fields: contains an associative array, the array key is the database field name, 
 *         the array value is the array field type (Ex. VARCHAR(255))
 * primary: contains the primary key field name (usually "id")
 * alter: contains an associative array, the array key is the database field name, 
 *        the array value is the alter SQL command to apply to the specific field (Ex. int(11) UNSIGNED NOT NULL AUTO_INCREMENT)
 */

namespace Firststep\Common\Builders;

class CreateQueryBuilder {
	
	private $tableName;
	private $engine;
	private $collate;
	private $charset;
	private $primary;
    private $fields;
    private $alter;
	
	public function __construct() {
		$this->tableName = '';
		$this->collate = 'utf8_bin';
		$this->engine = 'InnoDB DEFAULT';
		$this->charset = 'utf8';
		$this->primary = 'id';
	    $this->fields = array();
	    $this->alter = array();
	}

    /**
     * @param string $table_name
     */
    public function setTableName( string $tableName ) {
        $this->tableName = $tableName;
    }
	
    /**
     * @param string $collate
     */
    public function setCollate( string $collate ) {
        $this->collate = $collate;
    }
	
    /**
     * @param string $engine
     */
    public function setEngine( string $engine ) {
        $this->engine = $engine;
    }
	
    /**
     * @param string $charset
     */
    public function setCharset( string $charset ) {
        $this->charset = $charset;
    }
	
    /**
     * @param string $primary
     */
    public function setPrimary( string $primary ) {
        $this->primary = $primary;
    }
	
    /**
     * @param string $fields
     */
    public function setFields( array $fields ) {
        $this->fields = $fields;
    }
	
    /**
     * @param string $alter
     */
    public function setAlter( array $alter ) {
        $this->alter = $alter;
    }

    public function getCreateQuery() {
        $query = 'CREATE TABLE `'.$this->tableName.'` (';
    	foreach ($this->fields as $field => $type) {
    		$query .= '`'.$field.'` '.$type.', ';
    	}
		$query=rtrim($query,', ');
		$query .= ') ENGINE='.$this->engine.' CHARSET='.$this->charset.' COLLATE='.$this->collate.';';
		return $query;
    }
	
	/**
	 * ALTER TABLE `projectmessage` ADD PRIMARY KEY (`prjme_id`);
	 */
	public function getAddPrimaryKeyQuery() {
		return 'ALTER TABLE `'.$this->tableName.'` ADD PRIMARY KEY (`'.$this->primary.'`);';
	}
	
	/**
	 * ALTER TABLE `useroffice` MODIFY `usroff_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
	 */
	public function getAddAutoincrementQuery() {
		$type = 'int(10) UNSIGNED NOT NULL';
    	foreach ($this->fields as $field => $fieldtype) {
			if ( $field == $this->primary ) $type = $fieldtype;
    	}
		$type .= ' AUTO_INCREMENT';
		return 'ALTER TABLE `'.$this->tableName.'` MODIFY `'.$this->primary.'` '.$type.' ;';
	}
	
	/**
	 * TODO to be implemented, at the moment it does not work!!!
	 *
	 * ALTER TABLE `nontechnicalasset`
  	 *     ADD CONSTRAINT `nontechnicalasset_ibfk_1` FOREIGN KEY (`nta_categoryid`) REFERENCES `assetcategory` (`ac_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  	 *     ADD CONSTRAINT `nontechnicalasset_ibfk_2` FOREIGN KEY (`nta_parentid`) REFERENCES `asset` (`a_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  	 *     ADD CONSTRAINT `nontechnicalasset_ibfk_3` FOREIGN KEY (`nta_unitid`) REFERENCES `riskcentreunit` (`rcu_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  	 *     ADD CONSTRAINT `nontechnicalasset_ibfk_4` FOREIGN KEY (`nta_baseid`) REFERENCES `asset` (`a_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
	 */
	public function getAddAddkeyQuery() {
		$constraintsString = '';
		foreach ($this->constraints as $cons) {
			$constraintsString .= 'ADD CONSTRAINT `'.$cons->name.'` FOREIGN KEY (`'.$cons->foreignkey.'`) REFERENCES `'.$cons->referencestable.'` (`'.$cons->referencesfield.'`) ON DELETE '.$cons->ondelete.' ON UPDATE '.$cons->onupdate.',';
		}
		$constraintsString = rtrim($constraintsString,','); 
		$constraintsString .= ';';
		return 'ALTER TABLE `'.$this->tableName.'` '.$constraintsString.';';
	}

}
