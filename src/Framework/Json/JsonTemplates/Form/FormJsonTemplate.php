<?php

namespace Fabiom\UglyDuckling\Framework\Json\JsonTemplates\Form;

use Fabiom\UglyDuckling\Framework\Blocks\BaseHTMLForm;
use Fabiom\UglyDuckling\Framework\Json\JsonTemplates\JsonDefaultTemplateFactory;
use Fabiom\UglyDuckling\Framework\Json\JsonTemplates\JsonTemplate;
use Fabiom\UglyDuckling\Framework\Utils\UrlServices;

/**
 * User: Fabio Mattei
 * Date: 13/07/2018
 * Time: 12:00
 */
class FormJsonTemplate extends JsonTemplate {

    const blocktype = 'form';

    public function createForm() {
        $logger = $this->pageStatus->logger;
        $queryExecutor = $this->pageStatus->getQueryExecutor();

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

        $this->pageStatus->setLastEntity($entity);

        $formBlock = new BaseHTMLForm;
        $formBlock->setTitle($this->resource->get->form->title ?? '');
        $formBlock->setFormId($this->resource->get->form->formid ?? '');

        // Setting the action to link the form to
        if ( isset($this->resource->get->form->action) ) {
            $url = UrlServices::make_resource_url(
                $this->resource->get->form->action,
                $this->pageStatus
            );
            if ( isset($this->resource->get->form->action->resource) ) {
                $formBlock->addHiddenField('res', $this->resource->get->form->action->resource);    
            }
            if ( isset( $this->resource->get->form->action->parameters ) AND is_array($this->resource->get->form->action->parameters) ) {
                foreach ($this->resource->get->form->action->parameters as $par) {
                    $formBlock->addHiddenField($par->name, $this->pageStatus->getValue($par));
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
        $formBlock->addHiddenField('csrftoken', $_SESSION['csrftoken'] );
        foreach ($fieldRows as $row) {
            $formBlock->addRow();
            foreach ($row as $field) {
                $value = $this->pageStatus->getValue($field);
                if (in_array( $field->type, array('textfield', 'number') )) {
                    $formBlock->addGenericField( $field, $value ?? '');
                }
                elseif ($field->type === 'dropdown') {
                    $options = array();
                    foreach ($field->options as $op) {
                        $options[$op->value] = $op->label;
                    }
                    $formBlock->addDropdownField($field->name, $field->label, $options, $value ?? '', $field->width ?? '', $field->cssclass ?? '');
                }
                elseif ($field->type === 'sqldropdown') {
                    if ( isset($field->query) ) {
                        $queryExecutor->setQueryStructure( $field->query );

                        $result = $queryExecutor->executeSql();
                        $fieldOptions = $result->fetchAll();
                    } else {
                        $logger->write('ERROR <FormJsonTemplate> <sqldropdown> - Missing object query in json object', __FILE__, __LINE__);
                        $fieldOptions = array();
                    }

					$options = array();
					if (property_exists($field, 'options') AND is_array($field->options)) {    // || is_object($op)
                        foreach ($field->options as $op) {
                    	    $options[$op->value] = $op->label;
                        }
                    }
					foreach ($fieldOptions as $op) {
					    if ( !isset($field->valuesqlfield) ) {
					        $logger->write('ERROR <FormJsonTemplate> <sqldropdown> - Missing parameter valuesqlfield in json object', __FILE__, __LINE__);
					    }
					    if ( !isset($field->labelsqlfield) ) {
					        $logger->write('ERROR <FormJsonTemplate> <sqldropdown> - Missing parameter labelsqlfield in json object', __FILE__, __LINE__);
					    }
					    $options[$op->{$field->valuesqlfield}] = $op->{$field->labelsqlfield};
					}
                    $formBlock->addDropdownField($field->name, $field->label, $options, $value ?? '', $field->width ?? '', $field->cssclass ?? '');
                }
                elseif ($field->type === 'radiobutton') {
                    $formBlock->addRadioButtonField($field->name, $field->label, $value ?? '', $field->width ?? '', $field->checked ?? '', $field->cssclass ?? '');
                }
                elseif ($field->type === 'checkbox') {
                    $checkedValue = '';   // value the field should have if checked
                    $checkedString = '';  // string to put in actual HTML tag
                    if ( isset($field->checked) AND is_array($field->checked) ) {
                        $checkedValue = $this->pageStatus->getValueFromArrayValue($field->checked);
                    }
                    if ( $checkedValue == $value ) {
                        $checkedString = 'checked="checked"';
                    }
                    $formBlock->addCheckBoxField($field->name, $field->label, $value ?? '', $field->width ?? '', $checkedString ?? '', $field->cssclass ?? '');
                }
                elseif ($field->type === 'textarea') {
                    $formBlock->addTextAreaField($field->name, $field->label, $value ?? '', $field->width ?? '', $field->cssclass ?? '');
                }
                elseif ($field->type === 'date') {
                    if (!isset($field->placeholder)) { $field->placeholder = date('Y-m-d'); }
                    $formBlock->addGenericField( $field, ( isset($value) AND $value != '' ) ? $value : date('Y-m-d'), $field->cssclass ?? '');
                }
                elseif ($field->type === 'time') {
                    if (!isset($field->placeholder)) { $field->placeholder = date('H:i'); }
                    $formBlock->addGenericField( $field, ( isset($value) AND $value != '' ) ? $value : date('H:i'), $field->cssclass ?? '');
                }
                elseif ($field->type === 'infotext') {
                    $formBlock->addHelpingText( $field->label ?? '', $field->text ?? '', $field->width ?? '', $field->cssclass ?? '' );
                }
                elseif ($field->type === 'infovalue') {
                    $formBlock->addHelpingText( $field->label ?? '', $value ?? '', $field->width ?? '', $field->cssclass ?? '' );
                }
                elseif ($field->type === 'hidden') {
                    $formBlock->addHiddenField($field->name, $value);
                }
                elseif ($field->type === 'file') {
                    $formBlock->addFileUploadField($field->name, $field->label, $field->width ?? '', $field->cssclass ?? '');
                }
                elseif ($field->type === 'submitbutton') {
                    $formBlock->addSubmitButton( $field->name, $field->constantparameter ?? '', $field->label ?? '', $field->width ?? '12', $field->cssclass ?? '');
                }
                else {
                    $formBlock->addHTMLTag( JsonDefaultTemplateFactory::getHTMLTag( $field, $this->pageStatus, $this->jsonTabTemplates ) );
                }

            }
            $formBlock->closeRow('row '.$rowcounter);
            $rowcounter++;
        }
        return $formBlock;
    }

}
