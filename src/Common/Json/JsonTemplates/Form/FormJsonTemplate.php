<?php

/**
 * User: Fabio Mattei
 * Date: 13/07/2018
 * Time: 12:00
 */

namespace Fabiom\UglyDuckling\Common\Json\JsonTemplates\Form;

use Fabiom\UglyDuckling\Common\Blocks\BaseHTMLForm;
use Fabiom\UglyDuckling\Common\Json\JsonTemplates\JsonTemplate;

class FormJsonTemplate extends JsonTemplate {

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
                $this->queryExecuter->setLogger( $this->logger );
                $this->queryExecuter->setSessionWrapper( $this->sessionWrapper );
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
                if (in_array( $field->type, array('textfield', 'number') )) {
                    $formBlock->addGenericField( $field, $value ?? '');
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
                if ($field->type === 'date') {
                    if (!isset($field->placeholder)) { $field->placeholder = date('Y-m-d'); }
                    $formBlock->addGenericField( $field, $value ?? '');
                }
                if ($field->type === 'hidden') {
                    $formBlock->addHiddenField($field->name, $value);
                }
                if ($field->type === 'file') {
                    $formBlock->addFileUploadField($field->name, $field->label, $field->width);
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
