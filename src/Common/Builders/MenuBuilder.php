<?php

/**
 * User: Fabio Mattei
 * Date: 29/09/18
 * Time: 11.54
 */

namespace Firststep\Common\Builders;

use Firststep\Common\Blocks\BaseMenu;

class MenuBuilder {

    private $menuStructure;

    /**
     * @param mixed $infoStructure
     */
    public function setMenuStructure($menuStructure) {
        $this->menuStructure = $menuStructure;
    }

    public function createMenu() {
        // TODO this
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
