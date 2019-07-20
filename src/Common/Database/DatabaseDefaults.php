<?php

namespace Fabiom\UglyDuckling\Common\Database;

/**
 * This class defines all defaults setting for database handling
 */
class DatabaseDefaults {

    const DB_TABLE = 'abstract';
    const DB_TABLE_PK = 'abstract';
    const DB_TABLE_UPDATED_FIELD_NAME = 'abstract';
    const DB_TABLE_CREATED_FLIED_NAME = 'abstract';

	/**
	 * Convert a form field type like textarea or text to a database field type to apply by default
	 *
	 *
	 *
	 */
	public static function getCorrespondingDatabaseFieldType( string $fieldType ): string {
		switch ( $fieldType ) {
			case 'primary': return 'int(11) UNSIGNED NOT NULL';
		    case 'textarea': return 'TEXT';
			case 'textfield': return 'VARCHAR (255)';
		    case 'currency': return 'DECIMAL(16,2)';
		    case 'date': return 'DATE';
			case 'datetime': return 'DATETIME';
			case 'numeric': return 'INT(11)';
			case 'foreignkey': return 'INT(11) UNSIGNED';
		}
		return 'It is not possible to define a field type in DatabaseDefaults, type given: '.$fieldType;
	}

}
