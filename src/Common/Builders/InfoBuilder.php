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
		foreach ($this->infoStructure->rows as $row) {
			$formBlock->addRow();
			foreach ($row->fields as $field) {
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

}
