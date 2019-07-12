<?php

namespace Firststep\Common\Blocks;

use Firststep\Common\Blocks\BaseHTMLBlock;
use Firststep\Common\Blocks\ColWidth;

class BaseHTMLForm extends BaseHTMLBlock {

    private $title;
    private $subtitle;
    private $action;
    private $body;
    private $htmlTemplateLoader;

    function __construct() {
        $this->body = '';
		$this->adddate = false;
		$this->action = '';
        $this->body = '';
    }

    public function setHtmlTemplateLoader($htmlTemplateLoader) {
        $this->htmlTemplateLoader = $htmlTemplateLoader;
    }
	
	function setTitle( string $title ) {
		$this->title = $title;
	}
	
	function setSubTitle( string $subTitle ) {
		$this->subTitle = $subTitle;
	}
	
	function setAction( string $action ) {
		$this->action = $action;
	}

    function show(): string {
        return $this->htmlTemplateLoader->loadTemplateAndReplace(
            array('${title}', '${subtitle}', '${action}', '${body}'),
            array($this->title, $this->subtitle, $this->action, $this->body),
            'Form/body.html');
    }

    function addTextField( string $name, string $label, string $placeholder, string $value, string $width ) {
        $this->body .= $this->htmlTemplateLoader->loadTemplateAndReplace(
            array('${ColWidth}', '${name}', '${label}', '${value}', '${placeholder}'),
            array(ColWidth::getWidth(ColWidth::MEDIUM, $width), $name, $label, htmlspecialchars( $value ), $placeholder),
            'Form/textfield.html');
    }

    function addPasswordField( string $name, string $label, string $width ) {
        $this->body .= $this->htmlTemplateLoader->loadTemplateAndReplace(
            array('${ColWidth}', '${name}', '${label}'),
            array(ColWidth::getWidth(ColWidth::MEDIUM, $width), $name, $label),
            'Form/passwordfield.html');
    }

    function addTextAreaField( string $name, string $label, string $value, string $width ) {
        $this->body .= $this->htmlTemplateLoader->loadTemplateAndReplace(
            array('${ColWidth}', '${name}', '${label}', '${value}'),
            array(ColWidth::getWidth(ColWidth::MEDIUM, $width), $name, $label, htmlspecialchars( $value )),
            'Form/textarea.html');
    }

    /**
     * @param string $name
     * @param string $label
     * @param array $options
     * @param string $value
     * @param string $width
     *
     * Add a drop down field to the form
     *
     * Options must be given in the format:
     *   array( 'optvalue1' => 'opt label 1', 'optvalue2' => 'opt label 2' )
     */
    function addDropdownField( string $name, string $label, array $options, string $value, string $width ) {
        $htmloptions = '';
        foreach ($options as $key => $val) {
            $htmloptions .= $this->htmlTemplateLoader->loadTemplateAndReplace(
                array('${key}', '${selected}', '${val}'),
                array($key, ( $key==$value ? 'selected="selected"' : '' ), htmlspecialchars( $val )),
                'Form/selectfieldoption.html');
        }
        $this->body .= $this->body .= $this->htmlTemplateLoader->loadTemplateAndReplace(
            array('${ColWidth}', '${name}', '${label}', '${htmloptions}'),
            array(ColWidth::getWidth(ColWidth::MEDIUM, $width), $name, $label, $htmloptions),
            'Form/selectfield.html');
    }
	
	function addCurrencyField( string $name, string $label, string $placeholder, string $value, string $width ) {
        $this->body .= $this->htmlTemplateLoader->loadTemplateAndReplace(
            array('${ColWidth}', '${name}', '${label}', '${value}', '${placeholder}'),
            array(ColWidth::getWidth(ColWidth::MEDIUM, $width), $name, $label, htmlspecialchars( $value ), $placeholder),
            'Form/currencyfield.html');
	}
	
	function addDateField( string $name, string $label, string $value, string $width, string $placeholder ) {
        $this->body .= $this->htmlTemplateLoader->loadTemplateAndReplace(
            array('${ColWidth}', '${name}', '${label}', '${value}', '${placeholder}'),
            array(ColWidth::getWidth(ColWidth::MEDIUM, $width), $name, $label, htmlspecialchars( $value ), $placeholder),
            'Form/datefield.html');
	}

    /**
     * @param $field
     *
     * Filed is a object that contains a property for each of the properties of the filed we want to insert in the form
     * Each property corresponds to an HTML INPUT tag
     *
     * Ex:
     * $field->type = 'password'
     * $field->id   = 'myfieldid'
     * $field->name = 'myfieldname'
     *
     * Becomes: <input type="password" id="myfieldid" name="myfieldname" >
     *
     * The label property is reserved for the field label
     *
     */
    function addGenericField( $field, $value ) {
        $properties = '';
        foreach ($field as $key => $value) {
            if (!in_array( $key, array('label', 'width', 'row', 'value', 'sqlfield', 'name') )) { // forbidden properties
                $properties .= $key . '=' .'"' . $value . '" ' ;
            }
        }
        $this->body .= $this->htmlTemplateLoader->loadTemplateAndReplace(
            array('${ColWidth}', '${name}', '${label}', '${value}', '${properties}'),
            array(ColWidth::getWidth(ColWidth::MEDIUM, $field->width ?? 12), $field->name ?? '', $field->label ?? '', htmlspecialchars( $value ), $properties),
            'Form/genericfield.html');
    }

    function addFileUploadField( string $name, string $label, string $width ) {
        $this->body .= $this->htmlTemplateLoader->loadTemplateAndReplace(
            array('${ColWidth}', '${name}', '${label}'),
            array(ColWidth::getWidth(ColWidth::MEDIUM, $width), $name, $label),
            'Form/fileuploadfield.html');
    }

    function addHelpingText( string $title, string $text, string $width ) {
        $this->body .= $this->htmlTemplateLoader->loadTemplateAndReplace(
            array('${ColWidth}', '${title}', '${text}'),
            array(ColWidth::getWidth(ColWidth::MEDIUM, $width), $title, $text),
            'Form/helpingtext.html');
    }

    function addHiddenField( string $name, string $value ) {
        $this->body .= $this->htmlTemplateLoader->loadTemplateAndReplace(
            array('${name}', '${value}'),
            array($name, htmlspecialchars( $value )),
            'Form/hiddenfield.html');
    }

    function addSubmitButton( string $name = 'save', string $value = 'Save' ) {
        $this->body .= $this->htmlTemplateLoader->loadTemplateAndReplace(
            array('${name}', '${value}'),
            array($name, htmlspecialchars( $value )),
            'Form/submitbutton.html');
    }
	
	function addRow() {
        $this->body .= $this->htmlTemplateLoader->loadTemplateAndReplace(
            array(),
            array(),
            'Form/addrow.html');
    }
	
	function closeRow( string $comment = '' ) {
        $this->body .= $this->htmlTemplateLoader->loadTemplateAndReplace(
            array('${comment}'),
            array($comment),
            'Form/closerow.html');
	}

}
