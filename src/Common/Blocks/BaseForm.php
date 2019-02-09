<?php

namespace Firststep\Common\Blocks;

use Firststep\Common\Blocks\BaseBlock;
use Firststep\Common\Blocks\ColWidth;

class BaseForm extends BaseBlock {

    private $title;
    private $subtitle;
    private $action;
    private $body;

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
        $file = file_get_contents('Templates/HTML/Form/body.html');
        return str_replace(array('${title}', '${subtitle}', '${action}', '${body}'), array($this->title, $this->subtitle, $this->action, $this->body), $file);
    }

    function addTextField( string $name, string $label, string $placeholder, string $value, string $width ) {
        $this->body .= '<div class="form-group '.ColWidth::getWidth(ColWidth::MEDIUM, $width).'"><label for="'.$name.'">'.$label.'</label><input class="form-control" type="text" id="'.$name.'" name="'.$name.'" value="'.htmlspecialchars( $value ).'" placeholder="'.$placeholder.'"></div>';
    }

    function addPasswordField( string $name, string $label, string $width ) {
        $this->body .= '<div class="form-group '.ColWidth::getWidth(ColWidth::MEDIUM, $width).'"><label for="'.$name.'">'.$label.'</label><input class="form-control" type="password" id="'.$name.'" name="'.$name.'"></div>';
    }

    function addTextAreaField( string $name, string $label, string $value, string $width ) {
        $this->body .= '<div class="form-group '.ColWidth::getWidth(ColWidth::MEDIUM, $width).'"><label for="'.$name.'">'.$label.'</label><textarea class="form-control" id="'.$name.'" name="'.$name.'">'.htmlspecialchars( $value ).'</textarea></div>';
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
        $this->body .= '<div class="form-group '.ColWidth::getWidth(ColWidth::MEDIUM, $width).'"><label for="'.$name.'">'.$label.'</label><select class="form-control" id="'.$name.'" name="'.$name.'">';
        foreach ($options as $key => $val) {
            $this->body .= '<option value="'.$key.'" '.( $key==$value ? 'selected="selected"' : '' ).'>'.htmlspecialchars( $val ).'</option>';
        }
        $this->body .= '</select></div>';
    }
	
	function addCurrencyField( string $name, string $label, string $placeholder, string $value, string $width ) {
		$this->body .= '<div class="form-group '.ColWidth::getWidth(ColWidth::MEDIUM, $width).'"><label for="'.$name.'">'.$label.'</label><input class="form-control" type="number" id="'.$name.'" name="'.$name.'" value="'.htmlspecialchars( $value ).'" placeholder="'.$placeholder.'" min="0" step="0.01"></div>';
	}
	
	function addDateField( string $name, string $label, string $value, string $width ) {
		$this->body .= '<div class="form-group '.ColWidth::getWidth(ColWidth::MEDIUM, $width).'"><label for="'.$name.'">'.$label.'</label><input class="form-control" type="date" id="'.$name.'" name="'.$name.'" value="'.htmlspecialchars( $value ).'" ></div>';
	}
	
	function addDateField_oldstyle( string $name, string $label, string $value, string $width ) {
		$this->adddate = true;
		$this->body .= '<div class="form-group '.ColWidth::getWidth(ColWidth::MEDIUM, $width).'"><label for="'.$name.'">'.$label.'</label><input class="form-control" type="date" class="datepicker" id="'.$name.'" name="'.$name.'" value="'.date( 'd/m/Y', strtotime($value) ).'" ></div>';
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
	
    function addToHead(): string {
        $out = '';
        if ($this->adddate) {
            $out .= '<link rel="stylesheet" href="assets/lib/jquery-ui/jquery-ui.css">';
        }
        return $out;
    }

    function addToFoot(): string {
        $out = '';
        if ($this->adddate) {
            $out .= '<script src="assets/lib/jquery-ui/jquery-ui.min.js"></script>
 		   	            <script>
  		  		            $(function() {
    				            $( ".datepicker" ).datepicker({ dateFormat: "dd/mm/yy" });
  				            });
  			            </script>';
        }
        return $out;
    }

}
