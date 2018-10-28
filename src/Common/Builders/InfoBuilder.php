<?php

/**
 * User: Fabio Mattei
 * Date: 13/07/18
 * Time: 18.15
 */

namespace Firststep\Common\Builders;

use Firststep\Common\Blocks\BaseInfo;

class InfoBuilder {

    private $infoStructure;
    private $entity;

    /**
     * @param mixed $infoStructure
     */
    public function setFormStructure($infoStructure) {
        $this->infoStructure = $infoStructure;
    }

    /**
     * @param mixed $entity
	 * the $entity variable contains all values for the form
     */
    public function setEntity($entity) {
        $this->entity = $entity;
    }

    public function createInfo() {
		$formBlock = new BaseInfo;
		$formBlock->setTitle($this->infoStructure->title);
		$maxrows = $this->calculateMaxumumRowsNumber($this->formStructure->fields);
		$fieldRows = array();
		
		foreach ($this->infoStructure->fields as $field) {
			if( !array_key_exists($field->row, $fieldRows) ) $fieldRows[$field->row] = array();
			$fieldRows[$field->row][] = $field;
		}
		
		foreach ($fieldRows as $row) {
			$formBlock->addRow();
			foreach ($row as $field) {
				$fieldname = $field->value;
				$value = ($this->entity == null ? '' : ( isset($this->entity->$fieldname) ? $this->entity->$fieldname : '' ) );
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
			$formBlock->closeRow('row '.$row->row);
		}
        return $formBlock;
    }
	
	/**
	 * It checks all fields contained in the json description file and get the maximum row number
	 */
	public function calculateMaxumumRowsNumber() {
		$max = 1;
		foreach ($this->infoStructure->fields as $field) {
			if ( $field->row > $max ) $max = $field->row;
		}
		return $max;
	}

}
