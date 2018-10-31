<?php

/**
 * User: fabio
 * Date: 13/07/2018
 * Time: 12:00
 */

namespace Firststep\Common\Builders;

use Firststep\Common\Blocks\BaseForm;

class FormBuilder {

    private $formStructure;
    private $entity;

    /**
     * @param mixed $formStructure
     */
    public function setFormStructure($formStructure) {
        $this->formStructure = $formStructure;
    }

    /**
     * @param mixed $entity
	 * the $entity variable contains all values for the form
     */
    public function setEntity($entity) {
        $this->entity = $entity;
    }

    /**
     * Set the complete URL for the form action
     * @param action $action
     */
    public function setAction( $action ) {
        $this->action = $action;
    }

    public function createForm() {
		$formBlock = new BaseForm;
		$formBlock->setTitle($this->formStructure->title);
        $formBlock->setAction( $this->action );
		$maxrows = $this->calculateMaxumumRowsNumber($this->formStructure->fields);
		$fieldRows = array();
		
		foreach ($this->formStructure->fields as $field) {
			if( !array_key_exists($field->row, $fieldRows) ) $fieldRows[$field->row] = array();
			$fieldRows[$field->row][] = $field;
		}
		
		foreach ($fieldRows as $row) {
			$formBlock->addRow();
			foreach ($row as $field) {
				$fieldname = $field->sqlfield;
				$value = ($this->entity == null ? '' : ( isset($this->entity->{$fieldname}) ? $this->entity->{$fieldname} : '' ) );
                if ($field->type === 'textarea') {
                    $formBlock->addTextAreaField($field->name, $field->label, $value, $field->width);
                }
                if ($field->type === 'currency') {
                    $formBlock->addCurrencyField($field->name, $field->label, $field->placeholder, $value, $field->width);
                }
                if ($field->type === 'date') {
                    $formBlock->addDateField($field->name, $field->label, $value, $field->width);
                }
                if ($field->type === 'hidden') {
                    $formBlock->addHiddenField($field->name, $value);
                }
			}
			$formBlock->closeRow('row '.$row->row);
		}
        $formBlock->addRow();
        $formBlock->addSubmitButton( 'save', $this->formStructure->submitTitle );
        $formBlock->closeRow('row save');
        return $formBlock;
    }
	
	/**
	 * It checks all fields contained in the json description file and get the maximum row number
	 */
	public function calculateMaxumumRowsNumber() {
		$max = 1;
		foreach ($this->formStructure->fields as $field) {
			if ( $field->row > $max ) $max = $field->row;
		}
		return $max;
	}

}
