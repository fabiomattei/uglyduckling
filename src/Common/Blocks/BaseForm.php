<?php

namespace Firststep\Common\Blocks;

use Firststep\Common\Blocks\BaseBlock;

class BaseForm extends BaseBlock {

    private $title;
    private $subtitle;
    private $action;

    function __construct() {
        $this->body = '';
		$this->adddate = false;
		$this->action = '';
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
        $out = '<h3>' . $this->title . '</h3>';
        if ( $this->subtitle != '' ) {
            $out .= '<p>' . $this->subtitle . '</p>';
        }
        $out .= '<form action="'.$this->action.'" method="POST" class="form-horizontal">';
        $out .= $this->body;
        $out .= '</form>';
        return $out;
    }

    function addTextField( string $name, string $label, string $placeholder, string $value, string $width ) {
        $this->body .= '<div class="'.$width.'"><label for="'.$name.'">'.$label.'</label><input type="text" id="'.$name.'" name="'.$name.'" value="'.htmlspecialchars( $value ).'" placeholder="'.$placeholder.'"></div>';
    }

    function addPasswordField( string $name, string $label, string $width ) {
        $this->body .= '<div class="'.$width.'"><label for="'.$name.'">'.$label.'</label><input type="password" id="'.$name.'" name="'.$name.'"></div>';
    }

    function addTextAreaField( string $name, string $label, string $value, string $width ) {
        $this->body .= '<div class="'.$width.'"><label for="'.$name.'">'.$label.'</label><textarea id="'.$name.'" name="'.$name.'">'.htmlspecialchars( $value ).'</textarea></div>';
    }

    function addDropdownField( string $name, string $label, array $options, string $value, string $width ) {
        $this->body .= '<div class="'.$width.'"><label for="'.$name.'">'.$label.'</label><select id="'.$name.'" name="'.$name.'">';
        foreach ($options as $key => $val) {
            $this->body .= '<option value="'.$key.'" '.( $key==$value ? 'selected="selected"' : '' ).'>'.htmlspecialchars( $val ).'</option>';
        }
        $this->body .= '</select></div>';
    }
	
	function addCurrencyField( string $name, string $label, string $placeholder, string $value, string $width ) {
		$this->body .= '<div class="'.$width.'"><label for="'.$name.'">'.$label.'</label><input type="number" id="'.$name.'" name="'.$name.'" value="'.htmlspecialchars( $value ).'" placeholder="'.$placeholder.'" min="0" step="0.01"></div>';
	}
	
	function addDateField( string $name, string $label, string $value, string $width ) {
		$this->body .= '<div class="'.$width.'"><label for="'.$name.'">'.$label.'</label><input type="date" id="'.$name.'" name="'.$name.'" value="'.htmlspecialchars( $value ).'" ></div>';
	}
	
	function addDateField_oldstyle( string $name, string $label, string $value, string $width ) {
		$this->adddate = true;
		$this->body .= '<div class="'.$width.'"><label for="'.$name.'">'.$label.'</label><input type="date" class="datepicker" id="'.$name.'" name="'.$name.'" value="'.date( 'd/m/Y', strtotime($value) ).'" ></div>';
	}

    function addFileUploadField( string $name, string $label, string $width ) {
        $this->body .= '<div class="'.$width.'"><label for="'.$name.'">'.$label.'</label><input type="file" id="'.$name.'" name="'.$name.'"></div>';
    }

    function addHelpingText( string $title, string $text, string $width ) {
        $this->body .= '<div class="'.$width.'"><h5>'.$title.'</h5><p>'.$text.'</p></div>';
    }

    function addHiddenField( string $name, string $value ) {
        $this->body .= '<input type="hidden" name="'.$name.'" value="'.htmlspecialchars( $value ).'">';
    }

    function addSubmitButton( string $name = 'save', string $value = 'Save' ) {
        $this->body .= '<input type="submit" name="'.$name.'" value="'.htmlspecialchars( $value ).'"/>';
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
