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

    public function createForm() {
		$formBlock = new BaseForm;
		$formBlock->setTitle($this->formStructure->title);
		foreach ($this->formStructure->rows as $row) {
			$formBlock->addRow();
			foreach ($row->fields as $field) {
				$fieldname = $field->value;
				$value = ($this->entity == null ? '' : ( isset($this->entity->$fieldname) ? $this->entity->$fieldname : '' ) );
                if ($field->type === 'textarea') {
                    $formBlock->addTextAreaField($field->name, $field->label, $value, $field->width);
                }
                if ($field->type === 'currency') {
                    $formBlock->addCurrencyField($field->name, $field->label, '',$value, $field->width);
                }
                if ($field->type === 'date') {
                    $formBlock->addDateField($field->name, $field->label, $value, $field->width);
                }
			}
			$formBlock->closeRow('row '.$row->row);
		}
        return $formBlock;
    }

}
