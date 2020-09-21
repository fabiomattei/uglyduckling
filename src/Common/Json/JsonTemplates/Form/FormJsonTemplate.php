<?php

namespace Fabiom\UglyDuckling\Common\Json\JsonTemplates\Form;

use Fabiom\UglyDuckling\Common\Blocks\BaseHTMLForm;
use Fabiom\UglyDuckling\Common\Json\JsonTemplates\JsonTemplate;

/**
 * User: Fabio Mattei
 * Date: 13/07/2018
 * Time: 12:00
 */
class FormJsonTemplate extends JsonTemplate {

    const blocktype = 'form';

    public function createForm() {
        $logger = $this->jsonTemplateFactoriesContainer->getLogger();
        $htmlTemplateLoader = $this->jsonTemplateFactoriesContainer->getHtmlTemplateLoader();
        $pageStatus = $this->jsonTemplateFactoriesContainer->getPageStatus();
        $queryExecutor = $pageStatus->getQueryExecutor();

        // If there are dummy data they take precedence in order to fill the form
        if ( isset($this->resource->get->dummydata) ) {
            $entity = $this->resource->get->dummydata;
        } else {
            // If there is a query I look for data to fill the form,
            // if there is not query I do not
            if ( isset($this->resource->get->query) ) {
                $queryExecutor->setResourceName( $this->resource->name ?? 'undefined ');
                $queryExecutor->setQueryStructure( $this->resource->get->query );

                $result = $queryExecutor->executeSql();
                $entity = $result->fetch();
            } else {
                $entity = new \stdClass();
            }
        }

        $pageStatus->setLastEntity($entity);

		$formBlock = new BaseHTMLForm;
        $formBlock->setHtmlTemplateLoader( $htmlTemplateLoader );
		$formBlock->setTitle($this->resource->get->form->title ?? '');

		// Setting the action to link the form to
        if ( isset($this->resource->get->form->action) ) {
            $url = $this->jsonTemplateFactoriesContainer->getApplicationBuilder()->make_resource_url(
                $this->resource->get->form->action,
                $this->jsonTemplateFactoriesContainer->getApplicationBuilder()->getJsonloader(),
                $this->jsonTemplateFactoriesContainer->getPageStatus()
            );
            $formBlock->addHiddenField('res', $this->resource->get->form->action->resource);
            if ( isset( $this->resource->get->form->action->parameters ) AND is_array($this->resource->get->form->action->parameters) ) {
                foreach ($this->resource->get->form->action->parameters as $par) {
                    $formBlock->addHiddenField($par->name, $this->jsonTemplateFactoriesContainer->getPageStatus()->getValue($par));
                }
            }
        } else {
            $url = $this->action ?? '';
        }
        $formBlock->setAction( $url );

        $formBlock->setMethod( $this->resource->get->form->method ?? 'POST');
		$fieldRows = array();
		
		foreach ($this->resource->get->form->fields as $field) {
			if( !array_key_exists($field->row, $fieldRows) ) $fieldRows[$field->row] = array();
			$fieldRows[$field->row][] = $field;
		}
		
        $rowcounter = 1;
		foreach ($fieldRows as $row) {
			$formBlock->addRow();
			foreach ($row as $field) {
                $value = $pageStatus->getValue($field);
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
                if ($field->type === 'sqldropdown') {
                    if ( isset($field->query) ) {
                        $queryExecutor->setQueryStructure( $field->query );

                        $result = $queryExecutor->executeSql();
                        $fieldOptions = $result->fetchAll();
                    } else {
                        $logger->write('ERROR <FormJsonTemplate> <sqldropdown> - Missing object query in json object', __FILE__, __LINE__);
                        $fieldOptions = array();
                    }

                    $options = array();
                    foreach ($fieldOptions as $op) {
                        if ( !isset($op->valuesqlfield) ) {
                            $logger->write('ERROR <FormJsonTemplate> <sqldropdown> - Missing parameter valuesqlfield in json object', __FILE__, __LINE__);
                        }
                        if ( !isset($op->labelsqlfield) ) {
                            $logger->write('ERROR <FormJsonTemplate> <sqldropdown> - Missing parameter labelsqlfield in json object', __FILE__, __LINE__);
                        }
                        $options[$op->valuesqlfield] = $op->labelsqlfield;
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
                    $formBlock->addSubmitButton( $field->name, $field->constantparameter ?? '', $field->label ?? '', $field->width ?? '12' );
                }
			}
			$formBlock->closeRow('row '.$rowcounter);
            $rowcounter++;
		}
        return $formBlock;
    }

}
