<?php

namespace Fabiom\UglyDuckling\Framework\Blocks;

use Fabiom\UglyDuckling\Framework\Blocks\BaseHTMLBlock;
use Fabiom\UglyDuckling\Framework\Blocks\ColWidth;
use Fabiom\UglyDuckling\Common\Utils\HtmlTemplateLoader;

class BaseHTMLForm extends BaseHTMLBlock {

    protected $title;
    protected $subtitle;
    protected $action;
    protected $body;
    protected $method;
    protected HtmlTemplateLoader $htmlTemplateLoader;
    protected $formid;
    protected $adddate;

    function __construct() {
        parent::__construct();
        $this->body = '';
        $this->formid = '';
		$this->adddate = false;
		$this->action = '';
        $this->method = 'POST';
        $this->body = '';
    }

    public function setHtmlTemplateLoader( HtmlTemplateLoader $htmlTemplateLoader ) {
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

    function setMethod( string $method ) {
        $this->method = $method;
    }

    function setFormId( string $formid ) {
        $this->formid = $formid;
    }

    function show(): string {
        return $this->htmlTemplateLoader->loadTemplateAndReplace(
            array('${title}', '${subtitle}', '${action}', '${body}', '${method}', '${formid}'),
            array($this->title, $this->subtitle, $this->action, $this->body, $this->method, $this->formid),
            'Form/body.html');
    }

    function addTextField( string $name, string $label, string $placeholder, string $value, string $width ) {
        $this->body .= $this->htmlTemplateLoader->loadTemplateAndReplace(
            array('${ColWidth}', '${name}', '${label}', '${value}', '${placeholder}'),
            array(ColWidth::getWidth(ColWidth::MEDIUM, $width), $name, $label, htmlspecialchars( $value ), $placeholder),
            'Form/textfield.html');
    }

    function addPasswordField( string $name, string $label, string $width = '', string $cssClass = '' ) {
        $this->body .= $this->htmlTemplateLoader->loadTemplateAndReplace(
            array('${ColWidth}', '${name}', '${label}', '${cssClass}'),
            array(ColWidth::getWidth(ColWidth::MEDIUM, $width), $name, $label, $cssClass),
            'Form/passwordfield.html');
    }

    function addTextAreaField( string $name, string $label, string $value, string $width = '', string $cssClass = ''  ) {
        $this->body .= $this->htmlTemplateLoader->loadTemplateAndReplace(
            array('${ColWidth}', '${name}', '${label}', '${value}', '${cssClass}'),
            array(ColWidth::getWidth(ColWidth::MEDIUM, $width), $name, $label, htmlspecialchars( $value ), $cssClass),
            'Form/textarea.html');
    }

    function addRadioButtonField( string $name, string $label, string $value, string $width, string $checked, string $cssClass = '' ) {
        $this->body .= $this->htmlTemplateLoader->loadTemplateAndReplace(
            array('${ColWidth}', '${name}', '${label}', '${value}', '${checked}', '${cssClass}'),
            array(ColWidth::getWidth(ColWidth::MEDIUM, $width), $name, $label, htmlspecialchars( $value ), $checked ?? '', $cssClass),
            'Form/radiobuttonfield.html');
    }

    function addCheckBoxField( string $name, string $label, string $value, string $width, string $checked, string $cssClass = ''  ) {
        $this->body .= $this->htmlTemplateLoader->loadTemplateAndReplace(
            array('${ColWidth}', '${name}', '${label}', '${value}', '${checked}', '${cssClass}'),
            array(ColWidth::getWidth(ColWidth::MEDIUM, $width), $name, $label, htmlspecialchars( $value ), $checked ?? '', $cssClass),
            'Form/checkboxfield.html');
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
    function addDropdownField( string $name, string $label, array $options, string $value, string $width, string $cssClass = ''  ) {
        $htmloptions = '';
        foreach ($options as $key => $val) {
            $htmloptions .= $this->htmlTemplateLoader->loadTemplateAndReplace(
                array('${key}', '${selected}', '${val}', '${cssClass}'),
                array($key, ( $key==$value ? 'selected="selected"' : '' ), htmlspecialchars( $val ), $cssClass),
                'Form/selectfieldoption.html');
        }
        $this->body .= $this->htmlTemplateLoader->loadTemplateAndReplace(
            array('${ColWidth}', '${name}', '${label}', '${htmloptions}'),
            array(ColWidth::getWidth(ColWidth::MEDIUM, $width), $name, $label, $htmloptions),
            'Form/selectfield.html');
    }

    /**
     * @param $field
     *
     * Filed is a stdClass that contains a property for each of the properties of the filed we want to insert in the form
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
    function addGenericField( $field, $fieldValue ) {
        $properties = '';
        foreach ($field as $key => $value) {
            if (!in_array( $key, array('label', 'width', 'row', 'value', 'sqlfield', 'name') )) { // forbidden properties
                $properties .= $key . '=' .'"' . $value . '" ' ;
            }
        }
        $this->body .= $this->htmlTemplateLoader->loadTemplateAndReplace(
            array('${ColWidth}', '${type}', '${name}', '${label}', '${value}', '${properties}'),
            array(ColWidth::getWidth(ColWidth::MEDIUM, $field->width ?? 12), $field->type ?? 'text', $field->name ?? '', $field->label ?? '', htmlspecialchars( $fieldValue ), $properties),
            'Form/genericfield.html');
    }

    function addFileUploadField( string $name, string $label, string $width, string $cssClass = ''  ) {
        $this->body .= $this->htmlTemplateLoader->loadTemplateAndReplace(
            array('${ColWidth}', '${name}', '${label}', '${cssClass}'),
            array(ColWidth::getWidth(ColWidth::MEDIUM, $width), $name, $label, $cssClass),
            'Form/fileuploadfield.html');
    }

    function addHelpingText( string $title, string $text, string $width, string $cssClass = ''  ) {
        $this->body .= $this->htmlTemplateLoader->loadTemplateAndReplace(
            array('${ColWidth}', '${title}', '${text}', '${cssClass}'),
            array(ColWidth::getWidth(ColWidth::MEDIUM, $width), $title, $text, $cssClass),
            'Form/helpingtext.html');
    }

    function addHiddenField( string $name, string $value ) {
        $this->body .= $this->htmlTemplateLoader->loadTemplateAndReplace(
            array('${name}', '${value}'),
            array($name, htmlspecialchars( $value )),
            'Form/hiddenfield.html');
    }

    function addSubmitButton( string $name = 'save', string $value = 'Save', string $label = '', string $width = '12', string $cssClass = ''  ) {
        $this->body .= $this->htmlTemplateLoader->loadTemplateAndReplace(
            array('${name}', '${value}', '${label}', '${ColWidth}', '${cssClass}'),
            array($name, htmlspecialchars( $value ), $label, ColWidth::getWidth(ColWidth::MEDIUM, $width), $cssClass),
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

    function addHTMLTag( string $tag ) {
        $this->body .= $tag;
    }

}
