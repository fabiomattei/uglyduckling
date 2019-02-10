<?php

/**
 * User: Fabio Mattei
 * Date: 13/07/18
 * Time: 18.15
 */

namespace Firststep\Common\Builders;

use Firststep\Common\Blocks\BaseInfo;
use Firststep\Common\Database\QueryExecuter;
use Firststep\Common\Builders\BaseBuilder;

class InfoBuilder extends BaseBuilder {

    public function createInfo() {
        $this->queryExecuter->setDBH( $this->dbconnection->getDBH() );
        $this->queryExecuter->setQueryBuilder( $this->queryBuilder );
        $this->queryExecuter->setQueryStructure( $this->resource->get->query );
        if (isset( $this->parameters ) ) $this->queryExecuter->setGetParameters( $this->parameters );

        $result = $this->queryExecuter->executeQuery();
        $entity = $result->fetch();

		$formBlock = new BaseInfo;
		$formBlock->setTitle($this->resource->get->info->title ?? '');
		$fieldRows = array();
		
		foreach ($this->resource->get->info->fields as $field) {
			if( !array_key_exists(($field->row ?? 1), $fieldRows) ) $fieldRows[$field->row] = array();
			$fieldRows[($field->row ?? 1)][] = $field;
		}
		
        $rowcounter = 1;
		foreach ($fieldRows as $row) {
			$formBlock->addRow();
			foreach ($row as $field) {
				$fieldname = $field->value;
				$value = ($entity == null ? '' : ( isset($entity->$fieldname) ? $entity->$fieldname : '' ) );
                if ($field->type === 'textarea') {
                    $formBlock->addTextAreaField($field->label, $value, $field->width);
                }
                if ($field->type === 'currency') {
                    $formBlock->addCurrencyField($field->label, $value, $field->width);
                }
                if ($field->type === 'date') {
                    $formBlock->addDateField($field->label, $value, $field->width);
                }
			}
			$formBlock->closeRow('row '.$rowcounter);
            $rowcounter++;
		}
        return $formBlock;
    }

}
