<?php

namespace Firststep\Common\Blocks;

use Firststep\Common\Blocks\BaseBlock;
use Firststep\Common\Blocks\ColWidth;

class BaseForm extends BaseBlock {

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
            array('${ColWidth}', '${name}', '${label}', '${value}', '${$placeholder}'),
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
            array('${ColWidth}', '${name}', '${label}', '${value}', '${$placeholder}'),
            array(ColWidth::getWidth(ColWidth::MEDIUM, $width), $name, $label, htmlspecialchars( $value ), $placeholder),
            'Form/currencyfield.html');
	}
	
	function addDateField( string $name, string $label, string $value, string $width ) {
        $this->body .= $this->htmlTemplateLoader->loadTemplateAndReplace(
            array('${ColWidth}', '${name}', '${label}', '${value}', '${$placeholder}'),
            array(ColWidth::getWidth(ColWidth::MEDIUM, $width), $name, $label, htmlspecialchars( $value ), $placeholder),
            'Form/datefield.html');
	}

    function addFileUploadField( string $name, string $label, string $width ) {
        $this->body .= '<div class="form-group '.ColWidth::getWidth(ColWidth::MEDIUM, $width).'"><label for="'.$name.'">'.$label.'</label><input class="form-control" type="file" id="'.$name.'" name="'.$name.'"></div>';
    }

    function addHelpingText( string $title, string $text, string $width ) {
        $this->body .= '<div class="form-group '.ColWidth::getWidth(ColWidth::MEDIUM, $width).'"><h5>'.$title.'</h5><p>'.$text.'</p></div>';
    }

    function addHiddenField( string $name, string $value ) {
        $this->body .= '<input type="hidden" name="'.$name.'" value="'.htmlspecialchars( $value ).'">';
    }

    function addSubmitButton( string $name = 'save', string $value = 'Save' ) {
        $this->body .= '<input class="form-control" type="submit" name="'.$name.'" value="'.htmlspecialchars( $value ).'"/>';
    }
	
	function addRow() {
		$this->body .= '<div class="row">';
	}
	
	function closeRow( string $comment = '' ) {
		$this->body .= '</div>  <!-- '.$comment.' -->';
	}

    public function setHtmlTemplateLoader($htmlTemplateLoader) {
        $this->htmlTemplateLoader = $htmlTemplateLoader;
    }

}
