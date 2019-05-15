<?php

namespace Firststep\Common\Json;

use Firststep\Common\Json\Builders\CreateQueryBuilder;
use Firststep\Common\Database\DatabaseDefaults;
use Firststep\Common\Database\DocumentDao;

/**
 * It parses a resource in order to make the neccessary queries to create a table in the database
 */
class JsonCreateQueryParser {
	
	function __construct() {
		$this->createQueryBuilder = new CreateQueryBuilder();
	}
	
	public function parse( $resource ): array {
		if ( $resource->metadata->type == 'document' AND $resource->metadata->version == 1 ) return $this->parseDocumentV1( $resource );
		if ( $resource->metadata->type == 'entity' AND $resource->metadata->version == 1 ) return $this->parseEntityV1( $resource );
		return '';
	}

	public function parseDocumentV1( $resource ): array {
		$this->createQueryBuilder->setTableName( $resource->name );
		$this->createQueryBuilder->setPrimary( 'id' );
		
		$fields = array();
		$fields['id'] = DatabaseDefaults::getCorrespondingDatabaseFieldType( 'primary' );
		$fields['sourceuserid'] = DatabaseDefaults::getCorrespondingDatabaseFieldType( 'foreignkey' );
		$fields['sourcegroup'] = DatabaseDefaults::getCorrespondingDatabaseFieldType( 'textfield' );
		foreach ( $resource->fields as $field ) {
			if ( isset( $field->dbtype ) ) {
				$fields[$field->name] = $field->dbtype;
			} else {
				$fields[$field->name] = DatabaseDefaults::getCorrespondingDatabaseFieldType( $field->type );
			}
		}
		$fields[ DocumentDao::DB_TABLE_STATUS_FIELD_NAME ]   = DatabaseDefaults::getCorrespondingDatabaseFieldType( 'textfield' );
		$fields[ DocumentDao::DB_TABLE_CREATED_FIELD_NAME ]  = DatabaseDefaults::getCorrespondingDatabaseFieldType( 'datetime' );
		$fields[ DocumentDao::DB_TABLE_UPDATED_FIELD_NAME ]  = DatabaseDefaults::getCorrespondingDatabaseFieldType( 'datetime' );
		$fields[ DocumentDao::DB_TABLE_SENT_FIELD_NAME ]     = DatabaseDefaults::getCorrespondingDatabaseFieldType( 'datetime' );
		$fields[ DocumentDao::DB_TABLE_ACCEPTED_FIELD_NAME ] = DatabaseDefaults::getCorrespondingDatabaseFieldType( 'datetime' );
		$fields[ DocumentDao::DB_TABLE_REJECTED_FIELD_NAME ] = DatabaseDefaults::getCorrespondingDatabaseFieldType( 'datetime' );
		
		$this->createQueryBuilder->setFields( $fields );
		
		return array( 
			$this->createQueryBuilder->getCreateQuery(), 
			$this->createQueryBuilder->getAddPrimaryKeyQuery(),
			$this->createQueryBuilder->getAddAutoincrementQuery()
		);
	}
	
	public function parseEntityV1( $resource ): array {
		$this->createQueryBuilder->setTableName( $resource->tablename );
		$this->createQueryBuilder->setPrimary( $resource->primary );
		
		if ( isset( $resource->engine ) ) $this->createQueryBuilder->setEngine( $resource->engine );
		if ( isset( $resource->charset ) ) $this->createQueryBuilder->setCharset( $resource->charset );
		if ( isset( $resource->collate ) ) $this->createQueryBuilder->setCollate( $resource->collate );
		
		$fields = array();
		foreach ( $resource->fields as $field ) {
			$fields[$field->name] = DatabaseDefaults::getCorrespondingDatabaseFieldType( $field->type );
		}
		$this->createQueryBuilder->setFields( $fields );
		
		return array( 
			$this->createQueryBuilder->getCreateQuery(), 
			$this->createQueryBuilder->getAddPrimaryKeyQuery(),
			$this->createQueryBuilder->getAddAutoincrementQuery()
		);
	}
	
}
