<?php

/**
 * User: Fabio Mattei
 * Date: 13/07/2018
 * Time: 12:00
 */

namespace Firststep\Common\Json\Builders\Form;

use Firststep\Common\Blocks\BaseHTMLForm;
use Firststep\Common\Json\Builders\BaseBuilder;

class FormBuilder extends BaseBuilder {

    const blocktype = 'form';

    public function createForm() {
        // If there are dummy data they take precedence in order to fill the form
        if ( isset($this->resource->get->dummydata) ) {
            $entity = $this->resource->get->dummydata;
        } else {
            // If there is a query I look for data to fill the form,
            // if there is not query I do not
            if ( isset($this->resource->get->query) AND isset($this->dbconnection) ) {
                $this->queryExecuter->setDBH( $this->dbconnection->getDBH() );
                $this->queryExecuter->setQueryBuilder( $this->queryBuilder );
                $this->queryExecuter->setQueryStructure( $this->resource->get->query );
                if (isset( $this->parameters ) ) $this->queryExecuter->setGetParameters( $this->parameters );

                $result = $this->queryExecuter->executeQuery();
                $entity = $result->fetch();
            } else {
                $entity = new \stdClass();
            }
        }

		$formBlock = new BaseHTMLForm;
        $formBlock->setHtmlTemplateLoader( $this->htmlTemplateLoader );
		$formBlock->setTitle($this->resource->get->form->title ?? '');
        $formBlock->setAction( $this->action ?? '');
		$fieldRows = array();
		
		foreach ($this->resource->get->form->fields as $field) {
			if( !array_key_exists($field->row, $fieldRows) ) $fieldRows[$field->row] = array();
			$fieldRows[$field->row][] = $field;
		}
		
        $rowcounter = 1;
		foreach ($fieldRows as $row) {
			$formBlock->addRow();
			foreach ($row as $field) {
				$value = $this->getValue($field, $entity);
                if ($field->type === 'textfield') {
                    $formBlock->addTextField($field->name, $field->label, $field->placeholder, $value ?? '', $field->width);
                }
                if ($field->type === 'dropdown') {
                    $options = array();
                    foreach ($field->options as $op) {
                        $options[$op->value] = $op->label;
                    }
                    $formBlock->addDropdownField($field->name, $field->label, $options, $value ?? '', $field->width);
                }
				if ($field->type === 'textarea') {
                    $formBlock->addTextAreaField($field->name, $field->label, $value ?? '', $field->width);
                }
                if ($field->type === 'currency') {
                    $formBlock->addCurrencyField($field->name, $field->label, $field->placeholder, $value ?? '', $field->width);
                }
                if ($field->type === 'date') {
                    $formBlock->addDateField($field->name, $field->label, $value ?? '', $field->width, $field->placeholder ?? date('Y-m-d'));
                }
                if ($field->type === 'hidden') {
                    $formBlock->addHiddenField($field->name, $value);
                }
                if ($field->type === 'submitbutton') {
                    $formBlock->addSubmitButton( $field->name, $field->constantparameter ?? '' );
                }
			}
			$formBlock->closeRow('row '.$rowcounter);
            $rowcounter++;
		}
        return $formBlock;
    }

}
